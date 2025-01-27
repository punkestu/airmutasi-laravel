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
        // create at least 6 pair bg and fg colors
        const colors = [
            ['bg-[#f44336]', 'text-[#fff]'],
            ['bg-[#e91e63]', 'text-[#fff]'],
            ['bg-[#9c27b0]', 'text-[#fff]'],
            ['bg-[#673ab7]', 'text-[#fff]'],
            ['bg-[#3f51b5]', 'text-[#fff]'],
            ['bg-[#2196f3]', 'text-[#fff]'],
            ['bg-[#03a9f4]', 'text-[#fff]'],
            ['bg-[#00bcd4]', 'text-[#fff]'],
            ['bg-[#009688]', 'text-[#fff]'],
            ['bg-[#4caf50]', 'text-[#fff]'],
            ['bg-[#8bc34a]', 'text-[#fff]'],
            ['bg-[#cddc39]', 'text-[#333]'],
            ['bg-[#ffeb3b]', 'text-[#333]'],
            ['bg-[#ffc107]', 'text-[#333]'],
            ['bg-[#ff9800]', 'text-[#333]'],
            ['bg-[#ff5722]', 'text-[#fff]'],
            ['bg-[#795548]', 'text-[#fff]'],
            ['bg-[#9e9e9e]', 'text-[#333]'],
            ['bg-[#607d8b]', 'text-[#fff]'],
        ];
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
            nodeTemplate: (content) => {
                const color = colors[content.level % colors.length];
                return `<div onclick="toggleNode(${content.node_id})" class="text-black flex items-center justify-center h-full text-center hover:cursor-pointer ${color[0]} ${color[1]}">${content.name}</div>`
            },
            canvasStyle: 'border: 1px solid black;background: #f6f6f6;',
            enableToolbar: true,
        };

        var realData = [];
        const treeContainer = document.getElementById('svg-tree');
        const tree = new ApexTree(treeContainer, options);

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
                        node_id: 0,
                        name: 'MUTASI CABANG',
                        collapsed: false,
                        level: 0
                    },
                    children: data
                };
                realData = data;
                tree.render(filterCollapsed(data));
            })
            .catch(error => console.error(error));
    </script>
    <script>
        function toggleNode(id) {
            realData = toggleCollapsed(realData, id);
            tree.render(filterCollapsed(realData));
        }

        function toggleCollapsed(data, id) {
            if (data.data.node_id === id) {
                data.data.collapsed = !data.data.collapsed;
            } else if (data.children) {
                data.children = data.children.map(child => toggleCollapsed(child, id));
            }

            return data;
        }

        function filterCollapsed(data) {
            var result = JSON.parse(JSON.stringify(data));
            if (result && result.children) {
                if (result.data.collapsed) {
                    result.children = [];
                } else {
                    result.children = data.children.map(child => filterCollapsed(child));
                }
            }

            return result;
        }
    </script>
</body>

</html>
