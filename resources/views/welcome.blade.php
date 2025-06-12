<x-guest-layout>   <!-- meter esta merda a funcionar -->
    <x-slot:heading>
        Dashboard Page
    </x-slot:heading>
    <script type="module">
        var channel = Echo.channel('my-channel');
        channel.listen('.messageReceive', function (data) {
            console.log(data);
            alert(JSON.stringify(data));
        });
    </script>
</x-guest-layout>