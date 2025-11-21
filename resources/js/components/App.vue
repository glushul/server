<template>
  <div v-if="article" class="alert alert-primary" role="alert">
    Добавлена новая статья
    <strong>
      <a :href="`/article/${article.id}`">{{ article.name }}</a>
    </strong>
  </div>
</template>

<script>
export default {
  data() {
    return { article: null }
  },
  created() {
    window.Echo.channel('articles')
      .listen('NewArticleEvent', (e) => {
        this.article = e.article
        console.log('Новое уведомление:', e.article)
      })
  }
}
</script>
