<script setup>
  import Menu from "./components/Menu.vue";

  ((doc, wind) => {

    const n = doc.getElementsByTagName("script")[0],
        s = doc.createElement("script");

    s.type = "text/javascript";
    s.async = true;
    s.src = wind.location.href + "backend/pixel.php?ref="+doc.referrer+"&page=" + encodeURIComponent(wind.location.href);
    n.parentNode.insertBefore(s, n);

  })(document, window);
</script>

<template>
  <Menu />
</template>