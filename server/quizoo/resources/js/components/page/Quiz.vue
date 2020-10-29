<template>
  <div>
    <the-header/>
    <main>
      <div class="container">
        <article class="col-md-8 col-xs-12">
          <section>
            <h2 class="quiz-question-h2">
              <img class="quiz-question__logo" src="images/what-is-mark.png" />
              第{{ quizNumber }}問
            </h2>
            <p>{{ title }}</p>
            <div class="quiz-answer__list">
              <ul>
                <li v-for="(answer, index) in answers" :key="index">
                  <a>
                    <button
                      @click="goAnswer(Number(index))"
                      :disabled="isAlreadyAnswered"
                    >{{ index }}</button>
                    {{ answer }}
                  </a>
                </li>
              </ul>
            </div>
            <div class="text-right">カテゴリー: {{ categoryName }}</div>
          </section>
          <section>
            <h2 class="quiz-correct-h2">
              <img class="quiz-correct__logo" src="images/correct-mark.png" />正解
            </h2>
            <p>
              <button
                class="quiz-correct-answer"
                v-if="isAlreadyAnswered"
                disabled
                >{{ correctAnswerNo }}</button>
            </p>
            <button
              @click="goAnswer(0)"
              v-show="!isAlreadyAnswered"
            >正解を表示する</button>
            <div
              class="alert alert-info"
              v-show="isCorrect"
            ><strong>正解!</strong>
            </div>
            <div
              class="alert alert-danger"
              v-show="isMistake"
            ><strong>不正解!</strong>
            </div>
          </section>
          <section >
            <h2 class="quiz-commentary-h2">
              <img class="quiz-commentary__logo" src="images/commentary-mark.png" />解説
            </h2>
            <div
              class="quiz-commentary__text"
              v-if="isAlreadyAnswered"
              style="white-space:pre-wrap; word-wrap:break-word;"
            >{{ commentary }}</div>
            <button
              type="button"
              class="btn btn-primary center-block"
              @click="goNextQuiz"
              v-if="!isQuizFinish"
            >次の問題へ</button>
            <button
              type="button"
              data-toggle="modal"
              data-target="#modal-result"
              class="center-block"
              v-if="isQuizFinish"
              @click="showResult"
            >結果を見る</button>
          </section>
        </article>
        <the-sidebar/>
      </div>
    </main>
    <the-footer/>
    <the-modal :correctPercentageObject="correctPercentageObject" ref="modal" ></the-modal>
  </div>
</template>

<script>
import TheHeader from "../layout/TheHeader";
import TheFooter from "../layout/TheFooter";
import TheSidebar from "../layout/TheSidebar";
import TheModal from "../module/TheModal";

export default {
  components: {
    TheHeader,
    TheFooter,
    TheSidebar,
    TheModal,
  },
  data() {
    return {
      quizData: [],
      title: "",
      imageSrc: "",
      answers: [],
      commentary: "",
      correctAnswerNo: 0,
      isCorrect: false, //正解かどうか
      isMistake: false, //間違いかどうか
      isAlreadyAnswered: false, //回答済みかどうか
      isQuizFinish: false, //クイズが終了したかどうか
      score: 0,
      quizNumber: 1,
      categoryName: "",
      correctPercentageObject: {},
    }
  },
  mounted() {
    const categories = this.$route.query.categories; //$route.query.パラメータ名でその値が取得可能
    this.$http.get(`/api/quiz?categories=${categories}`).then(({ data }) => {
      this.quizData = data;
      this.findNextQuiz(0);
      console.log(this.quizData);
    });
  },
  methods: {
    goAnswer(selectAnswerNum) {
      if (selectAnswerNum === 0) {
        //何も選択肢を押していない状態
        this.isCorrect = false;
        this.isMistake = false;
      } else if (selectAnswerNum === Number(this.correctAnswerNo)) {
        //正解
        this.isCorrect = true;
        this.isMistake = false;
        this.score += 1;
      } else {
        //不正解
        this.isCorrect = false;
        this.isMistake = true;
      }
      // 回答は1度だけさせる
      this.isAlreadyAnswered = true;

      if (this.quizNumber >= 10) {
        this.endQuiz();
      }
    },
    findNextQuiz(quizNumber) {
      this.title = this.quizData[quizNumber].title;
      this.answers = {
        1: this.quizData[quizNumber].answer.answer_1,
        2: this.quizData[quizNumber].answer.answer_2,
        3: this.quizData[quizNumber].answer.answer_3,
        4: this.quizData[quizNumber].answer.answer_4
      };
      console.log(this.answers);
      this.commentary = this.quizData[quizNumber].answer.commentary;
      this.correctAnswerNo = this.quizData[quizNumber].answer.correct_answer_no;
      this.categoryName = this.quizData[quizNumber].category.name;
    },
    goNextQuiz() {
      if (this.quizNumber >= 10) {
        this.endQuiz();
      } else {
        this.findNextQuiz(this.quizNumber);
        this.quizNumber += 1; //問題番号を追加
        this.isCorrect = false;
        this.isMistake = false;
        this.isAlreadyAnswered = false;
      }
    },
    endQuiz() {
      this.isQuizFinish = true;
      this.answerNo = "-";
      this.isAlreadyAnswered = true;

      this.correctPercentageObject = {
        correctScore: this.score,
        mistakeScore: 10 - this.score,
      };
    },
    showResult() {
      this.$refs.modal.render();
    }
  }
};
</script>