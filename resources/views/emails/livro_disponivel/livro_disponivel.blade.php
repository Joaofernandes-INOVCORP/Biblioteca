<h1>O livro "{{ $livro->titulo }}" está disponível!</h1>

<p>Podes agora fazer a requisição através do nosso site.</p>
<a href="{{ route('livros.show', $livro) }}">Ver livro</a>