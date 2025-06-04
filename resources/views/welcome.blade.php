<x-guest-layout>   <!-- meter esta merda a funcionar -->
    <x-slot:heading>
        Dashboard Page
    </x-slot:heading>
    <script type="module">
        var channel = Echo.private('my-channel');
        channel.listen('.my-event', function (data) {
            console.log(data);
            alert(JSON.stringify(data));
        });
    </script>
</x-guest-layout>