'use strict';

const _ = require('lodash');
const Promise = require('bluebird');
const avModel = require('../library/AVModel');
const Model = require('../library/Model');
const moment = require('moment');

function init(){
    return importTrailer();
}

function importTrailer() {
    return avModel.queryAll('AGTrailer').then((trailers) => {
        let db = Model.connect('db_trailer');
        return Promise.each(trailers, (trailer) => {
            let uid = global.shareData.users[trailer.get('user').id];
            if (!uid) {
                return true;
            }
            return Model.insert(db, 't_trailer', {
                id: null,
                uid: uid,
                title: trailer.get('title'),
                order_num: trailer.get('orderNum'),
                time: moment(trailer.get('time')).format('X'),
                create_time: moment(trailer.createdAt).format('X'),
                update_time: moment(trailer.updatedAt).format('X'),
                deleted: 0,
                status: trailer.get('status'),
                cover: trailer.get('cover') || ''
            });
        }).then(() => {
            console.log('AGTrailer导入成功');
            return 'AGTrailer导入成功';
        });
    });
}


module.exports = {
    run: () => {
        return init();
    }
};