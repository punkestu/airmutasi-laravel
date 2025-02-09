<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('components/head')
    <title>Mutant | Profil</title>
</head>

<body class="font-sans tracking-wider">
    @include('components/header', ['static' => true])
    @include('components.modal-component')
    <main class="px-8 py-16">
        <div id="node-pusat" class="mb-2">
            <select class="cabang-list">
                @foreach ($cabangs as $cabang)
                    <option value="{{ $cabang->id }}">{{ $cabang->nama }}</option>
                @endforeach
            </select>
            <button class="tambah-node" data-idnode="pusat">Tambah</button>
        </div>
        <div id="nodes">
            @if ($nodes)
                @foreach ($nodes as $node)
                    @include('components.strukturtree', ['node' => $node])
                @endforeach
            @endif
        </div>
    </main>
    @include('components.footer')
    <script src="/script/nav.js"></script>
    <script src="/script/chatbot.js"></script>
    <script>
        document.querySelectorAll('.node-cabang').forEach((el) => {
            el.addEventListener('click', (e) => {
                const idnode = e.target.getAttribute('data-idnode');
                const child = document.querySelector(`#child-of-${idnode}`);
                child.classList.toggle('hidden');
            });
        });
        document.querySelectorAll('.tambah-node').forEach((el) => {
            el.addEventListener('click', (e) => {
                const idnode = e.target.getAttribute('data-idnode');
                const node = document.querySelector(`#node-${idnode}`);
                const cabang_id = node.querySelector('.cabang-list').value;
                const root_id = idnode === 'pusat' ? null : idnode;
                tambah(cabang_id, root_id);
            });
        });
        document.querySelectorAll('.edit-node').forEach((el) => {
            el.addEventListener('click', (e) => {
                const idnode = e.target.getAttribute('data-idnode');
                const node = document.querySelector(`#node-${idnode}`);
                const cabang_id = node.getAttribute('data-cabangid');
                const root_id = node.querySelector('.node-list').value;
                edit(cabang_id, root_id);
            });
        });
        document.querySelectorAll('.hapus-node').forEach((el) => {
            el.addEventListener('click', (e) => {
                const idnode = e.target.getAttribute('data-idnode');
                const node = document.querySelector(`#node-${idnode}`);
                const cabang_id = node.getAttribute('data-cabangid');
                hapus(cabang_id);
            });
        });
    </script>
    <script>
        function edit(cabang_id, root_id) {
            fetch("/api/struktur-cabang/edit", {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        cabang_id,
                        root_id: parseInt(root_id),
                    })
                }).then(response => response.json())
                .then(data => {
                    alert('Berhasil disimpan');
                    window.location.reload();
                })
                .catch(error => console.error(error));
        }

        function hapus(cabang_id) {
            fetch("/api/struktur-cabang/delete/" + cabang_id, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                }).then(response => response.json())
                .then(data => {
                    alert('Berhasil dihapus');
                    window.location.reload();
                })
                .catch(error => console.error(error));
        }

        function tambah(cabang_id, root_id = null) {
            const node_exists = document.querySelector(`[data-cabangid="${cabang_id}"]`);
            if (node_exists) {
                alert('Cabang sudah ada');
                return;
            }
            fetch("/api/struktur-cabang/edit", {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        cabang_id,
                        root_id,
                    })
                }).then(response => response.json())
                .then(data => {
                    alert('Berhasil disimpan');
                    window.location.reload();
                })
                .catch(error => console.error(error));
        }
    </script>
</body>
