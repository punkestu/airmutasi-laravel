<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('components/head')
    <title>Air Mutasi | Profil</title>
</head>

<body class="font-sans tracking-wider">
    @include('components/header', ['static' => true])
    @include('components.modal-component')
    <main class="px-8 py-16">
        <a href="/cabang/struktur/edit">Edit</a>
        <div id="svg-tree"></div>
    </main>
    @include('components.footer')
    <script src="/script/nav.js"></script>
    <script src="/script/chatbot.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apextree"></script>
    <script>
        const options = {
            contentKey: 'data',
            width: "100%",
            height: "100vh",
            nodeWidth: 250,
            nodeHeight: 50,
            fontColor: '#fff',
            borderColor: '#333',
            childrenSpacing: 50,
            siblingSpacing: 20,
            direction: 'left',
            enableExpandCollapse: true,
            nodeTemplate: (content) =>
                `<div class="text-black flex items-center justify-center h-full text-center">${content.name}</div>`,
            canvasStyle: 'border: 1px solid black;background: #f6f6f6;',
            enableToolbar: true,
        };

        fetch("/api/struktur-cabang", {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                },
            }).then(response => response.json())
            .then(data => {
                data = {
                    id: "0",
                    data: {
                        name: 'MUTASI CABANG',
                    },
                    children: data
                };
                const treeContainer = document.getElementById('svg-tree');
                const tree = new ApexTree(treeContainer, options);
                tree.render(data);
            })
            .catch(error => console.error(error));
    </script>
</body>

</html>
