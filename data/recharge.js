'use strict';

const _ = require('lodash');
const Promise = require('bluebird');
const avModel = require('../library/AVModel');
const Model = require('../library/Model');
const moment = require('moment');
const payMethod = {
    'android-alipay': 1,
    'android-wechat': 2,
    'apple-iap': 3,
};

//执行要在db_config之后
function init(){
    return avModel.queryAll('AGChargeRecord').then((results) => {
        return importRecharge(results);
    });
}

function importRecharge(data) {
    let db = Model.connect('db_recharge');

    return Promise.each(data, (record) => {
        let uid = global.shareData.users[record.get('user').id];
        if (!uid || !record.get('price')) {
            return true;
        }
        let rechargeJson = {
            uid: uid,
            source: payMethod[record.get('paymentChannel')],
            out_trade_no: record.get('tradeId'),
            transaction_id: record.get('appgameOrderId'),
            transaction_time: record.get('completeTime') ? moment(record.get('completeTime')).format('X') : 0,
            total_price: record.get('price')*100,   //单位分
            notify_data: '',
            doubtful: 0,
            doubt_msg: '',
            finished: record.get('status') === 'complete' ? 1 : 0,
            create_time: moment(record.createdAt).format('X')
        };
        if (!_.isEmpty(rechargeJson)){
            //7.24 充值列表那里，将IOS的充值列表的commodity_id放在10001-20000区间，安卓的放在20001-30000
            let commodityId = convertCommodityId(record.get('productId'));
            return Model.insert(db, 't_recharge_'+ _.padStart(uid % 100, 2, 0), rechargeJson).then(() => {
                let productInfo = global.shareData.products[record.get('productId')];
                if (!productInfo) {
                    return true;
                }
                return Model.insert(db, 't_recharge_commodity_'+_.padStart(uid % 100, 2, 0), {
                    uid: uid,
                    source: payMethod[record.get('paymentChannel')],
                    out_trade_no: record.get('tradeId'),
                    //commodity_id: record.get('productId'),
                    commodity_id: commodityId,
                    commodity_count: 1,
                    commodity_name: productInfo.commodity_name,
                    commodity_desc: productInfo.commodity_desc,
                    img_url: '',
                    price: record.get('price') * 100   //单位分
                }).then(() => {
                    return Model.insert(db, 't_recharge_item_'+_.padStart(uid % 100, 2, 0), {
                        uid: uid,
                        source: payMethod[record.get('paymentChannel')],
                        out_trade_no: record.get('tradeId'),
                        commodity_id: commodityId,
                        item_id: 1,
                        item_count: productInfo.count
                    });
                });
            });
        }
    }).then(() => {
        console.log('AGChargeRecord导入成功');
        return 'AGChargeRecord导入成功';
    });
}

function convertCommodityId(productId) {
    let prefix = '';
    if (productId.substr(4, 1) === '2') {
        prefix = '20';
    } else {
        prefix = '10';
    }
    return prefix + productId.substr(5, 3);
}


module.exports = {
    run: () => {
        return init();
    }
};