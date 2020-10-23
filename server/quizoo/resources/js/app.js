import Vue from 'vue'
import router from './router.js'
import SocialSharing from 'vue-social-sharing'
import axios from 'axios'

Vue.prototype.$http = axios; //axios使用時、$httpとして使えるようにする


Vue.use(SocialSharing);

new Vue({
  router: router, // routerにはrouter.jsファイルを設定します
}).$mount('#app'); // routerを適用する要素を設定(マウント)します