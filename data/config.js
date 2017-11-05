'use strict';

const _ = require('lodash');
const Promise = require('bluebird');
const avModel = require('../library/AVModel');
const Model = require('../library/Model');
const moment = require('moment');

function init(){
    return importGift().then((res) => {
        return res;
    }).then(() => {
        return importVersion();
    }).then(() => {
       return importChargeProduct();
    });
}

//门票目前是在客户端写死
function importTickets() {
    //TODO
    return '';
}

function importVersion() {
    return avModel.queryAll('AGVersion').then((versions) => {
        let db = Model.connect('db_config');
        let dataList = [];
        _.each(versions, (version) => {
            dataList.push([
                version.get('versionNum'),
                version.get('platform') === 'ios' ? 1 : 2,
                version.get('channel').id,  //TODO: market
                version.get('features'),
                version.get('source'),
                version.get('availability')+1,
                0, 0, 0, '',
                moment(version.createdAt).format('X'),
                moment(version.updatedAt).format('X')
            ]);
        });
        return Model.insertMulti(db, 't_version', dataList).then(() => {
            console.log('AGVersion导入成功');
            return 'AGVersion导入成功';
        });
    });
}

function importChargeVariable(products) {
    let jsonData = {};
    _.each(products, (product) => {
        jsonData[product.get('productId')] = {
            commodity_type: 1,
            commodity_name: product.get('name'),
            commodity_desc: product.get('info') || '',
            count: product.get('diamond')
        };
    });
    global.shareData.products = jsonData;
    return Promise.resolve(jsonData);
}

function importChargeProduct() {
    return avModel.queryAll('AGChargeProduct').then((products) => {
        return importChargeVariable(products).then(() => {
            let db = Model.connect('db_config');
            return Promise.each(products, (product) => {
                //7.24 充值列表那里，将IOS的充值列表的commodity_id放在10001-20000区间，安卓的放在20001-30000
                let commodityId = convertCommodityId(product.get('productId'));
                let data = {
                    commodity_id: commodityId,
                    //commodity_type: 1,
                    commodity_name: product.get('name'),
                    commodity_desc: product.get('info') || '',
                    img_url: '',
                    price: product.get('price')*100,    //单位分
                    sort_id: 0,
                    hidden: product.get('disabled')
                };
                return Model.insert(db, 't_commodity', data).then(() => {
                    return Model.insert(db, 't_commodity_items', {
                        commodity_id: commodityId,
                        item_id: 1,
                        item_count: product.get('diamond')
                    });
                });
            }).then(() => {
                console.log('AGChargeProduct导入成功');
                return 'AGChargeProduct导入成功';
            });
        });
    });
}

function importGiftVariable(gifts) {
    let jsonData = {};
    _.each(gifts, (gift) => {
        jsonData[gift.id] = gift.get('uid');
    });
    global.shareData.gifts = jsonData;
    return Promise.resolve(jsonData);
}

function importGift() {
    return avModel.queryAll('AGGift').then((gifts) => {
        return importGiftVariable(gifts).then(() => {
            let dataList = [];
            _.each(gifts, (gift) => {
                dataList.push([
                    gift.get('uid'),
                    1,
                    gift.get('name'),
                    '',
                    gift.get('webpThumbnail'),
                    gift.get('icon') ? gift.get('icon').url() : '',
                    gift.get('webpBigImage'),
                    gift.get('webBigImage') ? gift.get('webBigImage').url() : '',
                    gift.get('currencyType') === 'diamond' ? 1 : 2,
                    gift.get('price'),
                    gift.get('type') === 'combo' ? 1 : 0, gift.get('sort'),
                    0,
                    gift.get('disabled'),
                    0, 0, ''
                ]);
            });
            if (!_.isEmpty(dataList)) {
                let db = Model.connect('db_config');
                return Model.insertMulti(db, 't_gift', dataList).then(() => {
                    console.log('AGGift导入成功');
                    return 'AGGift导入成功';
                });
            }
        });
    });
}

function importPhotoFrameConfig() {
    // 直接写sql导入
    return '';
}

function importTaskConfig() {

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
