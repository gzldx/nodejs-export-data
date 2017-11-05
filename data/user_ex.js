'use strict';

const _ = require('lodash');
const Promise = require('bluebird');
const avModel = require('../library/AVModel');
const Model = require('../library/Model');
const moment = require('moment');

function init(){
    //TODO: ticket, task
    return true;
}


module.exports = {
    run: () => {
        return init();
    }
};