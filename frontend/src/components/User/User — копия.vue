<script>
export default {
  data() {
    return {
      id: null,
      site: null,
      time_live: null,
      scroll: null,
      history_clicks: null,
      browser: null,
      device: null,
      platform: null,
      ip: null,
      user_agent: null,

      showInfo: {
        history_clicks: false,
        user_agent: false,
      },
    }
  },
  mounted() {
    fetch('/backend/pixel.php', {
      method: 'GET',
      headers: {
        'Content-Type': 'application/json',
      },
    }).then(response => response.json()).then(data => {
      if (data.code === 200 && data.response.model !== undefined) {
        const model = data.response.model;

        this.id = model.id;
        this.site = model.site;
        this.time_live = model.time_live;
        this.scroll = model.scroll;
        this.history_clicks = model.history_clicks;
        this.browser = model.browser;
        this.device = model.device;
        this.platform = model.platform;
        this.ip = model.ip;
        this.user_agent = model.user_agent;
      }
    }).catch((error) => {

      console.error('Ошибка отправки:', error);
    });
  },
  methods: {
    toShowInfo(element) {
      this.showInfo[element] = true;
    },
    hideInfo(element) {
      this.showInfo[element] = false;
    }
  }
}

</script>

<template>
  <div class="table">
    <div class="tr title">
      <div class="td">
        id
      </div>
      <div class="td">
        Ссылка на сайт
      </div>
      <div class="td">
        Время нахождения на сайте (сек)
      </div>
      <div class="td">
        Максимальный процент скролла
      </div>
      <div class="td">
        Браузер
      </div>
      <div class="td">
        Девайс
      </div>
      <div class="td">
        Платформа(ОС)
      </div>
      <div class="td">
        IP
      </div>
    </div>

    <br>

    <div class="tr">
      <div class="td">
        {{ this.id }}
      </div>
      <div class="td">
        {{ this.site }}
      </div>
      <div class="td">
        {{ this.time_live }}
      </div>
      <div class="add" @mouseenter="toShowInfo('history_clicks')" @mouseleave="hideInfo('history_clicks')">
        {{ this.scroll }}
      </div>
      <div class="info" v-if="this.showInfo['history_clicks']">
        {{ this.history_clicks }}
      </div>
      <div class="td">
        {{ this.browser }}
      </div>
      <div class="td">
        {{ this.device }}
      </div>
      <div class="td">
        {{ this.platform }}
      </div>
      <div class="td add" @mouseenter="toShowInfo('user_agent')" @mouseleave="hideInfo('user_agent')">
        {{ this.ip }}
      </div>
      <div class="info" v-if="this.showInfo['user_agent']">
        {{ this.user_agent }}
      </div>
    </div>
  </div>
</template>

<style scoped>
.table {
  border: #1a1a1a 2px solid;
}
.tr {
  display: inline-flex;
  //justify-content: space-between;
}
.tr.title {
  font-weight: bolder;
}
.td {
  padding: 5px;
  margin: 5px;
  font-family: serif;
  font-size: small;
}
.info {
  position: absolute;
  top: 60%;
  left: 50%;
  background-color: #FFFAAFFF;
  border-radius: 5px;
}

.td.add {
  width: 100%;
  border-bottom: #ff2626 2px solid;
}
</style>