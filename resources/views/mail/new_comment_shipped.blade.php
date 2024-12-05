<x-mail::message>
# Для статьи "{{ $article_name }}" добавлен комментарий с текстом:

{{ $comment->desc }}

<x-mail::button :url="$url">
Accept
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
