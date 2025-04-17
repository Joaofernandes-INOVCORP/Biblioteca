@props(['active' => false])

@php
  $base = 'btn btn-lg ';
  $style = $active
    ? 'btn-soft'
    : 'btn-ghost';
  $classes = $base . ' ' . $style;
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
  {{ $slot }}
</a>
