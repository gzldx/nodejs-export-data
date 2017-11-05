'use strict';

const _ = require('lodash');
const Promise = require('bluebird');
const avModel = require('../library/AVModel');
const Model = require('../library/Model');
const moment = require('moment');

function init(){
    return avModel.queryAll('_User').then((results) => {
        return importUserVariable(results).then(() => {
            return importUsers(results);
        }).then(() => {
            return importUserRole(results);
        }).then(() => {
            return importUserRobots(results);
        }).then(() => {
            return importUserCertification();
        }).then(() => {
            return updateCertStatus(results);
        }).then(() => {
            return importUserMobileLogin(results);
        }).then(() => {
            return importUserWechatLogin();
        }).then(() => {
            return importUserQQLogin();
        }).then(() => {
            return importUserTPTLogin();
        });
    });
}

function importUserVariable(data) {
    let jsonData = {};
    _.each(data, (user) => {
        jsonData[user.id] = user.get('uid');
    });
    global.shareData.users = jsonData;
    global.shareData.cert = [];
    return Promise.resolve(jsonData);
}

function importUsers(data) {
    let db = Model.connect('db_user');

    return Promise.each(data, (user) => {
        let uid = user.get('uid');
        let userJson = {
            uid: uid,
            //username: user.get('username'),
            nick: user.get('nickname') ? new Buffer(user.get('nickname')).toString('base64') : '',
            sex: user.get('gender'),
            photo_url: user.get('avatar') || '',
            cover_url: user.get('cover') || '',
            introduction: user.get('lifeStation') ? new Buffer(user.get('lifeStation')).toString('base64') : '',
            check_status: changeCheckStatus(user.get('certification')),
            check_msg: '',
            //userCertification
            real_name: '',
            id_card: '',
            id_font: '',
            id_back: '',
            id_hand: '',
            //userAvatarFrame
            photo_frame_id: 0,
            photo_frame_expire_time: 0,
            completeness: 0,
            create_time: moment(user.createdAt).format('X'),
            update_time: moment(user.updatedAt).format('X'),
            deleted: 0,
            is_robot: user.get('isRobot'),
            remark: 0,
            last_token: '',
            platform: 0,
            device_type: '',
            market: 0
        };
        if (!_.isEmpty(userJson)){
            return Model.insert(db, 't_user_info_'+ _.padStart(uid % 100, 2, 0), userJson);
        }
    }).then(() => {
        console.log('_User导入成功');
        return '_User导入成功';
    });
}

function importUserCertification() {
    return avModel.queryAll('AGUserCertification').then((userCerts) => {
        let db = Model.connect('db_user');
        return Promise.each(userCerts, (userCert) => {
            if (!userCert.get('user')) {
                return;
            }
            let uid = global.shareData.users[userCert.get('user').id];
            if (!uid) {
                return;
            }
            let data = {
                real_name: userCert.get('realName'),
                id_card: userCert.get('idCardNumber'),
                id_font: userCert.get('idCardFrontPhoto'),
                id_back: userCert.get('idCardBackPhoto'),
                id_hand: userCert.get('idCardHandPhoto'),
            };
            if (!_.isEmpty(data)){
                return Model.update(db, 't_user_info_'+ _.padStart(uid % 100, 2, 0), data, {uid: uid}).then(() => {
                    return Model.insert(db, 't_user_certify_list', {
                        uid: uid,
                        create_time: moment(userCert.createdAt).format('X'),
                        status: 1,
                        reason: 'admin通过',
                        update_time: moment(userCert.updatedAt).format('X'),
                        last_username: 'admin'
                    });
                });
            }
        }).then(() => {
            console.log('AGUserCertification导入成功');
            return 'AGUserCertification导入成功';
        });
    });
}

function updateCertStatus(data) {
    let db = Model.connect('db_user');

    return Promise.each(data, (user) => {
        let uid = user.get('uid');
        let status = user.get('certification');
        if (status === 2) {
            return Model.update(db, 't_user_certify_list', {status: 2}, {uid: uid});
        }
    }).then(() => {
        console.log('Check status更新成功');
        return 'Check status更新成功';
    });
}

function importUserAvatarFrame() {
    //TODO 24h过期，暂时不需要导出
}

function importUserMobileLogin(users) {
    let db = Model.connect('db_user');

    return Promise.each(users, (user) => {
        let uid = user.get('uid');
        if (!user.get('mobilePhoneNumber')) {
            return;
        }
        return Model.insert(db, 't_user_login_'+ _hashTableId(user.get('mobilePhoneNumber')), {
            id: user.get('mobilePhoneNumber'),
            id_source: 0,
            uid: uid,
            password: '',
            create_time: moment(user.createdAt).format('X'),
            deleted: 0
        }).then(() => {
            return Model.insert(db, 't_user_bind_'+ _.padStart(uid % 100, 2, 0), {
                uid: uid,
                id: user.get('mobilePhoneNumber'),
                id_source: 0,
                create_time: moment(user.createdAt).format('X'),
                deleted: 0
            });
        });
    }).then(() => {
        console.log('_User\'s mobilePhoneNumber导入成功');
        return '_User\'s mobilePhoneNumber导入成功';
    });
}

function importUserWechatLogin() {
    return avModel.queryAll('AGUserWxConnect').then((wxConnects) => {
        let db = Model.connect('db_user');
        return Promise.each(wxConnects, (wxConnect) => {
            if (wxConnect.get('deleted') === true) {
                return;
            }
            let uid = global.shareData.users[wxConnect.get('user').id];
            if (!uid) {
                return;
            }
            return Model.insert(db, 't_user_login_'+ _hashTableId(wxConnect.get('unionid')), {
                id: wxConnect.get('unionid'),
                id_source: 1,
                uid: uid,
                password: '',
                create_time: moment(wxConnect.createdAt).format('X'),
                deleted: 0
            }).then(() => {
                return Model.insert(db, 't_user_bind_'+ _.padStart(uid % 100, 2, 0), {
                    uid: uid,
                    id: wxConnect.get('unionid'),
                    id_source: 1,
                    create_time: moment(wxConnect.createdAt).format('X'),
                    deleted: 0
                });
            });
        }).then(() => {
            console.log('AGUserWxConnect导入成功');
            return 'AGUserWxConnect导入成功';
        });
    });
}

function importUserQQLogin() {
    return avModel.queryAll('AGUserQqConnect').then((qqConnects) => {
        let db = Model.connect('db_user');
        return Promise.each(qqConnects, (qqConnect) => {
            if (qqConnect.get('deleted') === true) {
                return;
            }
            let uid = global.shareData.users[qqConnect.get('user').id];
            if (!uid) {
                return;
            }
            return Model.insert(db, 't_user_login_'+ _hashTableId(qqConnect.get('unionid')), {
                id: qqConnect.get('unionid'),
                id_source: 2,
                uid: uid,
                password: '',
                create_time: moment(qqConnect.createdAt).format('X'),
                deleted: 0
            }).then(() => {
                return Model.insert(db, 't_user_bind_'+ _.padStart(uid % 100, 2, 0), {
                    uid: uid,
                    id: qqConnect.get('unionid'),
                    id_source: 2,
                    create_time: moment(qqConnect.createdAt).format('X'),
                    deleted: 0
                });
            });
        }).then(() => {
            console.log('AGUserQqConnect导入成功');
            return 'AGUserQqConnect导入成功';
        });
    });
}

function importUserTPTLogin() {
    return avModel.queryAll('AGUserTptConnect').then((tptConnects) => {
        let db = Model.connect('db_user');
        return Promise.each(tptConnects, (tptConnect) => {
            return Model.insert(db, 't_user_login_'+ _hashTableId(tptConnect.get('openid')), {
                id: tptConnect.get('openid'),
                id_source: 3,
                uid: tptConnect.get('uid'),
                password: '',
                create_time: moment(tptConnect.createdAt).format('X'),
                deleted: 0
            });
        }).then(() => {
            console.log('AGUserTptConnect导入成功');
            return 'AGUserTptConnect导入成功';
        });
    });
}

function importUserRole(data) {
    let db = Model.connect('db_user');
    return Promise.each(data, (user) => {
        let roles = user.get('roles');
        if (_.indexOf(roles, 'anchor') < 0) {
            return true;
        }
        let uid = user.get('uid');
        return Model.insert(db, 't_user_role_'+ _.padStart(uid % 100, 2, 0), {
            uid: uid,
            role_id: 1,
            create_time: 0,
            deleted: 0
        });
    }).then(() => {
        console.log('User Role导入成功');
        return 'User Role导入成功';
    });
}

function importUserRobots(data) {
    //导入机器人
    let db = Model.connect('db_user');
    let robotList = [];
    _.each(data, (user) => {
        if (!user.get('isRobot')) {
            return true;
        }
        let uid = user.get('uid');
        robotList.push([
            uid, moment(user.createdAt).format('X'), moment(user.createdAt).format('X'), 0
        ]);
    });
    return Model.insertMulti(db, 't_user_robot', robotList).then(() => {
        console.log('_User Robot导入成功');
        return '_User Robot导入成功';
    });
}

function importUserForbidden() {
    //TODO
    return '';
}

//["晴天","辽宁公会","任玩","vink","老王","安氏","马惠","长沙传输","元森泰","XI","弘谷互娱","铭森","鸿煜","超神","乐灵团","试播号","创点","灵动","WY","奥巴","美咖","英众","耳朵","Nice","娱加","卓宝","奥特莱斯"]
function importAsso() {
    let db = Model.connect('db_user');
    return Model.insertMulti(db, 't_user_guild', [
        [1, '晴天', 0, 0, 0],
        [2, '辽宁公会', 0, 0, 0],
        [3, '任玩', 0, 0, 0],
        [4, 'vink', 0, 0, 0],
        [5, '老王', 0, 0, 0],
        [6, '安氏', 0, 0, 0],
        [7, '马惠', 0, 0, 0],
        [8, '长沙传输', 0, 0, 0],
        [9, '元森泰', 0, 0, 0],
        [10, 'XI', 0, 0, 0],
        [11, '弘谷互娱', 0, 0, 0],
        [12, '铭森', 0, 0, 0],
        [13, '鸿煜', 0, 0, 0],
        [14, '超神', 0, 0, 0],
        [15, '乐灵团', 0, 0, 0],
        [16, '试播号', 0, 0, 0],
        [17, '创点', 0, 0, 0],
        [18, '灵动', 0, 0, 0],
        [19, 'WY', 0, 0, 0],
        [20, '奥巴', 0, 0, 0],
        [21, '美咖', 0, 0, 0],
        [22, '英众', 0, 0, 0],
        [23, '耳朵', 0, 0, 0],
        [24, 'Nice', 0, 0, 0],
        [25, '娱加', 0, 0, 0],
        [26, '卓宝', 0, 0, 0],
        [27, '奥特莱斯', 0, 0, 0]
    ]);
}

function importUserAsso(users) {
    let db = Model.connect('db_user');
    let asso = {
        '晴天': 1,
        '辽宁公会': 2,
        '任玩': 3,
        'vink': 4,
        '老王': 5,
        '安氏': 6,
        '马惠': 7,
        '长沙传输': 8,
        '元森泰': 9,
        'XI': 10,
        '弘谷互娱': 11,
        '铭森': 12,
        '鸿煜': 13,
        '超神': 14,
        '乐灵团': 15,
        '试播号': 16,
        '创点': 17,
        '灵动': 18,
        'WY': 19,
        '奥巴': 20,
        '美咖': 21,
        '英众': 22,
        '耳朵': 23,
        'Nice': 24,
        '娱加': 25,
        '卓宝': 26,
        '奥特莱斯': 27
    };
    return Promise.each(users, (user) => {
        let uid = user.get('uid');
        if (!user.get('association')) {
            return;
        }
        return Model.insert(db, 't_user_belong_guild', {
            uid: uid,
            gid: asso[user.get('association')],
            create_time: 0,
            update_time: 0,
            deleted: 0
        }).then(() => {
            return Model.insert(db, 't_user_role_'+ _.padStart(uid % 100, 2, 0), {
                uid: uid,
                role_id: 2,
                create_time: 0,
                deleted: 0
            });
        });
    }).then(() => {
        console.log('_User\'s guild导入成功');
        return '_User\'s guild导入成功';
    });
}

function _hashTableId(key) {
    let hashVal = 1806;
    for (let i=0; i < key.length; i++) {
        hashVal += (hashVal << 5) + key[i].charCodeAt();
        hashVal = hashVal % 100;
    }
    return _.padStart(hashVal, 2, 0);
}

function changeCheckStatus(val) {
    let status = {
        0: 0,
        1: 1,
        2: 3,
        10: 2
    };
    return status[val];
}

module.exports = {
    run: () => {
        return init();
    }
};