'use strict';

const _ = require('lodash');
const Promise = require('bluebird');
const avModel = require('../library/AVModel');
const Model = require('../library/Model');
const moment = require('moment');

function init(){
    return importFollowRecords().then(() => {
        return importBlockRecords();
    });
}

function importFollowRecords() {
    return avModel.queryAll('_Follower').then((results) => {
        let db = Model.connect('db_user_relative');
        return Promise.each((results), (record) => {
            let uid = global.shareData.users[record.get('follower').id];
            let f_uid = global.shareData.users[record.get('user').id];
            if (!uid || !f_uid) {
                return;
            }
            return Model.query(db, 'INSERT INTO t_attention_'+ _.padStart(uid % 100, 2, 0)+ ' SET `uid` = '+uid+', `f_uid` = '+f_uid+', `initiative` = 1, `update_time` = '+moment(record.updatedAt).format('X') +' ON DUPLICATE KEY UPDATE initiative = 1').then(() => {
                return Model.query(db, 'INSERT INTO t_attention_'+ _.padStart(f_uid % 100, 2, 0)+ ' SET `uid` = '+f_uid+', `f_uid` = '+uid+', `passive` = 1, `update_time` = '+moment(record.updatedAt).format('X') +' ON DUPLICATE KEY UPDATE passive = 1')
            });
        });
    }).then(() => {
        console.log('_Follower导入成功');
        return '_Follower导入成功';
    });
}

function importBlockRecords() {
    return avModel.queryAll('AGBlockList').then((results) => {
        let dataList = [];
        let db = Model.connect('db_user_relative');
        _.each((results), (record) => {
            let uid = global.shareData.users[record.get('user').id];
            let block_uid = global.shareData.users[record.get('blockUser').id];
            if (!uid || !block_uid) {
                return;
            }
            dataList.push([uid, block_uid, moment(record.createdAt).format('X')]);
        });
        return Model.insertMulti(db, 't_user_black', dataList).then(() => {
            console.log('AGBlockList导入成功');
            return 'AGBlockList导入成功';
        });
    });
}

module.exports = {
    run: () => {
        return init();
    }
};