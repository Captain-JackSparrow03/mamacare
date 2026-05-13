<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="h-screen flex">

<div class="hidden md:block w-1/2 bg-cover"
     style="background-image: url('/images/mama.jpg');">
</div>

<div class="w-full md:w-1/2 flex items-center justify-center">
    <div class="w-full max-w-md p-8">

        <h2 class="text-2xl font-bold mb-6">Inscription</h2>

        <form method="POST" action="/register">
            @csrf

            <input type="text" name="name" placeholder="Nom"
                   class="w-full mb-4 p-3 border rounded-lg">

            <input type="email" name="email" placeholder="Email"
                   class="w-full mb-4 p-3 border rounded-lg">

            <button class="w-full bg-pink-500 text-white p-3 rounded-lg">
                S'inscrire
            </button>
        </form>

    </div>
</div>

</body>
</html>