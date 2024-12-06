<script>
    export default {
    data() { return { article: null } },
        created() {
            window.Echo.channel('test').listen('NewArticleEvent', (article) => {
                console.log(article);
                this.article=article.article;
            })
        }
    }
</script>

<template>
    <div v-if="article != null" class="alert alert-primary" role="alert">
        Опубликована новая статья <a :href="`/article/${ article.id }`">"{{ article.name }}"</a>
    </div>
</template>