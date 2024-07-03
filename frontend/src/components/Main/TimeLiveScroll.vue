<script>
export default {
  data() {
    return {
      maxScrollPercent: 0,

      openTime: null,
      startTime: null,
      accumulatedTime: 0,
    };
  },
  created() {
    this.openTime = new Date();
    document.addEventListener('visibilitychange', this.handleVisibilityChange);
    this.startTimer();
  },
  mounted() {
    document.addEventListener('scroll', this.handleScroll);
  },
  beforeDestroy() {
    document.removeEventListener('visibilitychange', this.handleVisibilityChange);
    this.stopTimer();
    this.send(this.accumulatedTime, this.maxScrollPercent);
  },


  methods: {
    handleScroll() {
      const scrollTop = window.scrollY || document.documentElement.scrollTop;
      const docHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
      const scrollPercent = (scrollTop / docHeight) * 100;

      if (scrollPercent > this.maxScrollPercent) {
        this.maxScrollPercent = scrollPercent;
      }
    },

    startTimer() {
      console.log('startTimer');
      this.startTime = Date.now();
    },
    stopTimer() {
      console.log('stopTimer');
        if (this.startTime) {
          this.accumulatedTime += Date.now() - this.startTime;
          this.startTime = null;
        }
    },
    handleVisibilityChange() {
      if (document.hidden) {
        this.stopTimer();
      } else {
        this.startTimer();
      }
    },


    send(seconds, maxScrollPercent) {
      const send_seconds = Math.floor(seconds / 1000);

      fetch('/backend/pixel.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ time_live: send_seconds, scroll: maxScrollPercent }),
      }).then(response => response.json()).then(data => {

        console.log(data);
      }).catch((error) => {

        console.error('Ошибка отправки:', error);
      });
    },

    manualBeforeDestroy() {
      document.removeEventListener('visibilitychange', this.handleVisibilityChange);
      this.stopTimer();
      this.send(this.accumulatedTime, this.maxScrollPercent);
    },
  },
}
</script>

<template>
  <h2 style="margin-top: 10%">{{ this.openTime }}</h2>
  <button @click="manualBeforeDestroy">Покинуть сайт (имитация)</button>

  <h1>Максимальный процент скролла: {{ maxScrollPercent }}%</h1>
  <div style="height: 2000px;"></div>
</template>

