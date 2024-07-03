<script setup>
  import {ref, onMounted} from 'vue';
  import {routes, findComponentByPath} from '../route.js';

  const currentRoute = ref(window.location.pathname);
  const currentComponent = ref(null);

  const navigate = (path) => {
    window.history.pushState({}, path, window.location.origin + path);
    currentRoute.value = path;

    renderComponent();
  };

  const renderComponent = () => {
    const matchedRoute = findComponentByPath(currentRoute.value, routes);

    if (matchedRoute) {
      currentComponent.value = matchedRoute.component;
    } else {
      currentComponent.value = null;
    }
  };

  onMounted(() => {
    window.addEventListener('popstate', () => {
      currentRoute.value = window.location.pathname;

      renderComponent();
    });

    renderComponent();
  });

</script>

<template>
  <div>
    <nav class="menu">
      <a href="/" @click.prevent="navigate('/')">Main</a>
      <a href="/user" @click.prevent="navigate('/user')">Users</a>
    </nav>

    <component :is="currentComponent"></component>
  </div>
</template>


<style scoped>
  .menu {
    position: absolute;
    top: 0;
    right: 0;

    display: inline-flex;
    justify-content: space-around;
    width: 100%;
    border-bottom: #2642b8 2px solid;
  }

  a {
    margin-top: 15px;
    width: fit-content;
    margin-bottom: 20px;
  }
</style>