'use strict';

//db_item 账单，记录交易和送礼的每一笔信息，用于查账
//道具ID: 1钻石，2大圣币，3蟠桃

const _ = require('lodash');
const Promise = require('bluebird');
const avModel = require('../library/AVModel');
const Model = require('../library/Model');
const moment = require('moment');

function init(){
    return importUserAccount().then((res) => {
        return res;
    }).then(() => {
        return importUserRemainingWealth();
    }).then(() => {
        return importChargeRecord();
    }).then(() => {
        return importGiftRecord();
    });
}

//6.21 t_user_item新增get_month, deleted字段
function importUserAccount() {
    return avModel.queryAll('AGAccount').then((accounts) => {
        let db = Model.connect('db_item');
        return Promise.each(accounts, (account) => {
            let dataList = [];
            if (!account.get('user')) {
                return;
            }
            let uid = global.shareData.users[account.get('user').id];
            if (!uid) {
                return;
            }
            //1是钻石，2是大圣币，3是蟠桃
            if (account.get('diamond')) {
                dataList.push([uid, 1, account.get('diamond'), 0]);
            }
            if (account.get('coin')) {
                dataList.push([uid, 2, account.get('coin'), 0]);
            }
            if (account.get('wealth')) {
                dataList.push([uid, 3, account.get('wealth')*10, 0]);
            }
            if (!_.isEmpty(dataList)){
                return Model.insertMulti(db, 't_user_item_'+ _.padStart(uid % 100, 2, 0), dataList);
            }
        }).then(() => {
            console.log('AGAccount导入成功');
            return 'AGAccount导入成功';
        });
    });
}

function importUserRemainingWealth() {
    return avModel.queryAll('AGAccount').then((accounts) => {
        let db = Model.connect('db_withdraw');
        return Promise.each(accounts, (account) => {
            if (!account.get('user')) {
                return;
            }
            let uid = global.shareData.users[account.get('user').id];
            if (!uid) {
                return;
            }
            if (account.get('remainingWealth')) {
                return Model.insert(db, 't_gift_statistics', {
                    role_id: 1,
                    month: 8,
                    year: 2017,
                    month_start_money: account.get('remainingWealth'),
                    uid: uid,
                    real_name: ''
                });
            }
        }).then(() => {
            console.log('AGAccount Remaining Wealth导入成功');
            return 'AGAccount Remaining Wealth导入成功';
        });
    });
}

//source来源：1充值（钻石+） 2送礼（大圣币-或钻石-） 3收礼（蟠桃+） 4钻石转大圣币（钻石-，大圣币+） 5提现（蟠桃-）6任务（大圣币+）7首次登录（大圣币+）'

function importChargeRecord() {
    return avModel.queryAll('AGChargeRecord').then((records) => {
        let db = Model.connect('db_item');
        return Promise.each(records, (record) => {
            if (record.get('status') !== 'complete') {
                return;
            }
            let uid = global.shareData.users[record.get('user').id];
            if (!uid) {
                return;
            }
            let data = {
                uid: uid,
                create_time: moment(record.createdAt).format('X'),
                create_time_usec: 0,     //usec是记录微秒部分，6位
                item_id: 1,
                item_count: record.get('diamond'),
                trans_before: 0,
                params: '',
                source: 1   //1充值（钻石+）
            };
            return Model.insert(db, 't_item_trans_'+ _.padStart(uid % 100, 2, 0), data);
        }).then(() => {
            console.log('AGChargeRecord导入成功');
            return 'AGChargeRecord导入成功';
        });
    });
}

function importGiftRecord() {
    //TODO: 道具变化记录
    //数量多，分页导入
    return avModel.getRecordCount('AGGiftSendRecord').then((count) => {
        let pages = Math.ceil(count / 1000);
        let tagArr = [];
        for (let index = 0; index < pages; index++) {
            tagArr.push(index);
        }
        return tagArr;
    }).then((tagArr) => {
        return Promise.each(tagArr, (tag) => {
            let db = Model.connect('db_item');
            return avModel.queryPage('AGGiftSendRecord', tag).then((list) => {
                return Promise.each(list, (record) => {
                    let fromUid = global.shareData.users[record.get('from').id];
                    let toUid = global.shareData.users[record.get('to').id];
                    let giftId = global.shareData.gifts[record.get('gift').id] || 0;
                    if (!fromUid || !toUid) {
                        return;
                    }
                    let sendRecord = {
                        uid: fromUid,
                        seq_id: record.id,
                        group_id: global.shareData.videos[record.get('video').id],
                        gift_id: giftId,
                        combo: 0,
                        bundle_num: 1,
                        receiver_uid: toUid,
                        item_id: record.get('type') === 'diamond' ? 1 : 2,
                        item_count: record.get('amount'),
                        source: 2,
                        create_time: moment(record.createdAt).format('X'),
                        platform: 0,
                        device_type: '',
                        market: '',
                        gift_name: '',
                        peach_count: record.get('totalWealth') * 100
                    };
                    return Model.insert(db, 't_gift_send_record_'+ _.padStart(fromUid % 100, 2, 0), sendRecord).then(() => {
                        let recvRecord = {
                            uid: toUid,
                            seq_id: record.id,
                            group_id: global.shareData.videos[record.get('video').id],
                            gift_id: giftId,
                            combo: 0,
                            bundle_num: 1,
                            sender_uid: fromUid,
                            item_id: record.get('type') === 'diamond' ? 1 : 2,
                            item_count: record.get('amount'),
                            source: 2,
                            create_time: moment(record.createdAt).format('X'),
                            gift_name: '',
                            peach_count: record.get('totalWealth') * 100
                        };
                        return Model.insert(db, 't_gift_recv_record_'+ _.padStart(toUid % 100, 2, 0), recvRecord);
                    });
                });
            });
        }).then(() => {
            console.log('AGGiftSendRecord导入成功');
            return 'AGGiftSendRecord导入成功';
        });
    });
}

module.exports = {
    run: () => {
        return init();
    }
};
