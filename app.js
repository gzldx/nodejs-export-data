'use strict';

const Promise = require('bluebird');
const _ = require('lodash');
const path = require('path');

global.shareData = {};

const importModules = ['user', 'user_relative', 'config', 'live', 'item', 'recharge', 'trailer'];
const filePathArr = _.map(importModules, (name) => {
    return path.join(__dirname + '/data/', name);
});

//导入开始
return Promise.each(filePathArr, (filePath) => {
    let importer = require(filePath);
    return importer.run();
}).then(() => {
    console.log('Import finished!');
    process.exit();
}).catch((err) => {
    console.error(err);
    process.exit();
});