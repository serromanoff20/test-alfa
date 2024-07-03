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
  <div class="info" v-if="this.showInfo['history_clicks']">
    {{ this.history_clicks }}
  </div>
  <div class="info" v-if="this.showInfo['user_agent']">
    {{ this.user_agent }}
  </div>

  <ul>
    <li>id - {{ this.id }}</li>
    <li>site - {{ this.site }}</li>
    <li>time_live - {{ this.time_live }}</li>
    <li class="add" @mouseenter="toShowInfo('history_clicks')" @mouseleave="hideInfo('history_clicks')">
      scroll - {{ this.scroll }}
    </li>
    <li>browser - {{ this.browser }}</li>
    <li>device - {{ this.device }}</li>
    <li>platform - {{ this.platform }}</li>
    <li class="add" @mouseenter="toShowInfo('user_agent')" @mouseleave="hideInfo('user_agent')">
      ip - {{ this.ip }}
    </li>
  </ul>
</template>

<style scoped>
.info {
  position: absolute;
  top: 10%;
  left: 25%;

  padding: 2% 1%;
  width: 50%;
  background-color: #FFFAAFFF;
  border-radius: 10px;
}

.add {
  border: #ddca67 1px solid;
}
</style>