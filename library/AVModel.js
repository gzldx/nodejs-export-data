'use strict';

const config = require('../config/config');
const AV = require('leanengine');
const _ = require('lodash');
const Promise = require('bluebird');

//初始化
AV.initialize(config.leancloud.appId, config.leancloud.appKey, config.leancloud.masterKey);
//使用masterkey
AV.Cloud.useMasterKey();

function queryAll(table){
    let size = 1000;
    let query = new AV.Query(table);
    query.addAscending('createdAt');
    //特殊处理
    if (table === 'AGVideoLiveMessage') {
        query.include('file');
    }
    return query.count().then((count) => {
        if (count === 0) {
            return [];
        }
        let promiseArr = [];
        let pages = Math.ceil(count / size);
        for (let index = 0; index < pages; index++) {
            if (index && index > 0) {
                query.skip(index * size);
            }
            query.limit(size);
            promiseArr.push(query.find());
        }
        return promiseArr;
    }).then((promiseArr) => {
        if (_.isEmpty(promiseArr)) {
            return [];
        } else {
            let results = [];
            return Promise.each(promiseArr, (list) => {
                _.each(list, (result) => {
                    results.push(result);
                });
                return 'ok';
            }).then(() => {
                return results;
            }).catch((error) => {
                console.error(table, ' query error: ',error);
                return [];
            });



            // return AV.Promise.all(promiseArr).then((lists) => {
            //     let results = [];
            //     _.each(lists, (list) => {
            //         _.each(list, (result) => {
            //             results.push(result);
            //         });
            //     });
            //     return results;
            // }).catch((error) => {
            //     console.error(table, ' query error: ',error);
            //     return [];
            // });
        }
    });
}

function queryPage(table, pageNum) {
    let size = 1000;
    let query = new AV.Query(table);
    query.addAscending('createdAt');
    query.skip(pageNum * size);
    query.limit(size);
    return query.find();
}

function getRecordCount(table) {
    let query = new AV.Query(table);
    return query.count();
}

module.exports = {
    queryAll: queryAll,
    queryPage: queryPage,
    getRecordCount: getRecordCount
}