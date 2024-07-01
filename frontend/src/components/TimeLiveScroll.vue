<script>
export default {
  data() {
    return {
      startTime: null,
      endTime: null,
      maxScrollPercent: 0,
    };
  },
  mounted() {
    this.startTime = new Date();
    window.addEventListener('scroll', this.handleScroll);
  },
  beforeDestroy() {
    this.endTime = new Date();

    const timeSpent = this.endTime - this.startTime;

    const secondsSpent = Math.floor(timeSpent / 1000);

    window.removeEventListener('scroll', this.handleScroll);

    this.sendTimeSpent(secondsSpent, this.maxScrollPercent);
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
    sendTimeSpent(seconds, maxScrollPercent) {
      fetch('/backend/pixel.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({ time_live: seconds, scroll: maxScrollPercent }),
      }).then(response => response.json()).then(data => {

        console.log(data);
      }).catch((error) => {

        console.error('Ошибка отправки:', error);
      });
    },
    manualBeforeDestroy() {
      this.endTime = new Date();

      const timeSpent = this.endTime - this.startTime;

      const secondsSpent = Math.floor(timeSpent / 1000);

      window.removeEventListener('scroll', this.handleScroll);

      this.sendTimeSpent(secondsSpent, this.maxScrollPercent);
    }
  },
}
</script>

<template>
<!--  <h2>{{ this.startTime }}</h2>-->
<!--  <button @click="manualBeforeDestroy">Покинуть сайт (имитация)</button>-->

<!--  <h1>Максимальный процент скролла: {{ maxScrollPercent }}%</h1>-->
<!--  <div style="height: 2000px;"></div>-->
</template>
