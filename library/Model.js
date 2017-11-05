'use strict';

const config = require('../config/config');
const _ = require('lodash');
const mysql = require('mysql');
const Promise = require('bluebird');
const dbPool = {

};

function connect(key) {
    if (dbPool[key]) {
        return dbPool[key];
    } else {
        dbPool[key] = mysql.createPool(config.mysql[key]);
        return dbPool[key];
    }
}

function insert(db, table, data){
    return new Promise(function (resolve, reject) {
        db.getConnection(function(err, connection) {
            // if (err) {
            //     return reject(err);
            // }
            if (err) {
                console.error(err);
            }
            //connection.query('INSERT INTO ' + table + ' SET ?', data, function (error, result) {
            connection.query({sql: 'INSERT INTO ' + table + ' SET ?', values: data, timeout: 600000}, function (error, result) {
                connection.release();
                if (error) {
                    console.log(error);
                    //return reject(error);
                }
                return resolve('ok');
            });
        });
    });
}

/**
 *
 * @param db
 * @param table
 * @param array dataList 两维数组 [[1, 'a', 'b'], [2, 'c', 'd']]
 */
function insertMulti(db, table, dataList) {
    return new Promise(function (resolve, reject) {
        db.getConnection(function(err, connection) {
            if (err) {
                return reject(err);
            }
            connection.query('INSERT INTO ' + table + ' VALUES ?', [dataList], function (error, result) {
                connection.release();
                if (error) {
                    console.log(error);
                    //return reject(error);
                }
                return resolve('ok');
            });
        });
    });
}

function update(db, table, data, condition) {
    return new Promise(function (resolve, reject) {
        db.getConnection(function(err, connection) {
            if (err) {
                return reject(err);
            }
            connection.query('UPDATE ' + table + ' SET ? WHERE ?', [data, condition], function (error, result) {
                connection.release();
                if (error) {
                    console.log(error);
                    //return reject(error);
                }
                return resolve('ok');
            });
        });
    });
}

function query(db, sql) {
    return new Promise(function (resolve, reject) {
        db.getConnection(function(err, connection) {
            if (err) {
                return reject(err);
            }
            connection.query(sql, function (error, result) {
                connection.release();
                if (error) {
                    console.log(error);
                    //return reject(error);
                }
                return resolve('ok');
            });
        });
    });
}

function replace(db, table, data){
    return new Promise(function (resolve, reject) {
        db.getConnection(function(err, connection) {
            // if (err) {
            //     return reject(err);
            // }
            if (err) {
                console.error(err);
            }
            //connection.query('INSERT INTO ' + table + ' SET ?', data, function (error, result) {
            connection.query({sql: 'REPLACE INTO ' + table + ' SET ?', values: data, timeout: 600000}, function (error, result) {
                connection.release();
                if (error) {
                    console.log(error);
                    //return reject(error);
                }
                return resolve('ok');
            });
        });
    });
}

module.exports = {
    connect: connect,
    insert: insert,
    insertMulti: insertMulti,
    update: update,
    query: query,
    replace: replace
}
