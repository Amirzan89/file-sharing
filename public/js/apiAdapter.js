const axios = require("axios");
const baseURL = window.location.origin;
// const { TIMEOUT } = process.env;
const TIMEOUT = 5000;
const instance = axios.create({
    baseURL: baseURL,
    timeout: parseInt(TIMEOUT),
});
module.exports = instance;
