<template>
  <div>
    <the-header/>
    <main>
      <div class="container">
        <article class="col-md-8 col-xs-12">
          <section class="home-quiz__introduction">
            <h2 class="home-quiz__introduction-h2">
              <img class="home-quiz__introduction-h2-logo" src="/images/what-is-mark.png" />4 Answers Quizとは?
            </h2>
            <p>4 Answers Quizとはビジネスマナーから一般常識に至るまで様々なクイズを4択で出題するWEBアプリです。</p>
            <p>何度もトライしてみて正解率100%を目指してみてください。</p>
          </section>
          <section class="home-quiz__setting">
            <h2 class="home-quiz__setting-h2">
              <img class="home-quiz__setting-h2-logo" src="/images/directory-icon.png" />出題設定
            </h2>
            <form>
              <label v-for="category in categories" :key="category.id">
                <input type="checkbox" v-model="categoryIds" :value="category.id">{{ category.name }}&ensp;
              </label>
              <div class>
                全項目チェック
                <button type="button" name="check_all" id="check-all" value="1">ON</button>
                <button type="button" name="check_all_off" id="check-all-off" value="1">OFF</button>
              </div>
              <button type="submit" class="btn btn-primary" @click.stop.prevent="goQuiz()">出題開始</button>
              <input type="hidden" name="_token" value />
            </form>
          </section>
          <section class="home-quiz__ranking">
            <h2 class="home-quiz__ranking-h2">
              <img class="home-quiz__ranking-h2-logo" src="/images/graph-icon.png" />ランキング
            </h2>
            <div>
              <label>
                <input class="ranking-radio" type="radio" name="ranking-radio" value="1" checked />総合
              </label>
              <label>
                <input class="ranking-radio" type="radio" name="ranking-radio" value="2" />今月
              </label>
              <label>
                <input class="ranking-radio" type="radio" name="ranking-radio" value="3" />今週
              </label>
            </div>
            <div class="home_quiz__ranking-chart">
              <bar-chart/>
            </div>
          </section>
          <section class="home__notice">
            <h2 class="home__notice-h2">
              <img class="home__notice-h2-logo" src="/images/news-icon.png" />お知らせ情報
            </h2>
            <dl v-for="info in informations" :key="info.id">
              <dt>{{info.created_at}}</dt>
              <dd>{{info.information}}</dd>
            </dl>
          </section>
        </article>
        <the-sidebar/>
      </div>
    </main>

  <the-footer/>
  </div>
</template>

<script>
import TheHeader from "../layout/TheHeader";
import TheFooter from "../layout/TheFooter";
import TheSidebar from "../layout/TheSidebar";
import BarChart from "../module/BarChart";

export default {
  components: {
    TheHeader, //'the-header': TheHeaderの省略
    TheFooter,
    TheSidebar,
    BarChart,
  },

  data() {
    return {
      categoryIds: [1],
      informations: [],
      categories: [],
    }
  },
  methods: {
    goQuiz() {
      this.$router.push("/quiz?categories=" + this.categoryIds);// $route.pushでリロードなしで遷移
    }
  },
  mounted() { //DOM生成後のため画面反映遅い
    //Vue.prototype.$http = axios;
    this.$http.get('/api/category').then(({ data }) => {
      this.categories= data;
    });
    this.$http.get('/api/information').then(({ data }) => {
      this.informations = data;
    });
  }
};
</script>