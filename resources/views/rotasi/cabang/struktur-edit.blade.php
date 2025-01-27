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
        <div>
            <select id="tambah-select-cabang">
                @foreach ($cabangs as $cabang)
                    <option value="{{ $cabang->id }}">{{ $cabang->nama }}</option>
                @endforeach
            </select>
            <button onclick="tambah()">Tambah</button>
        </div>
        <div id="nodes">
            @foreach ($nodes as $node)
                <div class="flex items-center justify-between" id="node-{{ $node->cabang_id }}">
                    <p class="text-lg font-bold">{{ $node->cabang->nama }}</p>
                    <aside>
                        <select>
                            <option value="">PUSAT</option>
                            @foreach ($nodes as $snode)
                                <option value="{{ $snode->id }}" @if ($node->root_id == $snode->id) selected @endif>
                                    {{ $snode->cabang->nama }}
                                </option>
                            @endforeach
                        </select>
                        <button onclick="edit({{ $node->cabang_id }})">Simpan</button>
                        <button onclick="hapus({{ $node->cabang_id }})">Hapus</button>
                    </aside>
                </div>
            @endforeach
        </div>
    </main>
    <template id="node-template">
        <p class="text-lg font-bold" id="nama"></p>
        <aside>
            <select>
                <option value="">PUSAT</option>
                @foreach ($nodes as $snode)
                    <option value="{{ $snode->id }}">
                        {{ $snode->cabang->nama }}
                    </option>
                @endforeach
            </select>
            <button id="simpan">Simpan</button>
            <button id="hapus">Hapus</button>
        </aside>
    </template>
    @include('components.footer')
    <script src="/script/nav.js"></script>
    <script src="/script/chatbot.js"></script>
    <script>
        function edit(id) {
            const cabang_id = id;
            const root_id = document.getElementById(`node-${id}`).querySelector('select').value;
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
                    console.log(data);
                    const newNodes = data.cabangNodes;
                    document.querySelectorAll('#nodes > div select').forEach(node => {
                        const selectedNode = node.value;
                        node.innerHTML = '';
                        node.innerHTML = '<option value="">PUSAT</option>';
                        newNodes.forEach(newNode => {
                            const option = document.createElement('option');
                            option.value = newNode.id;
                            option.textContent = newNode.cabang.nama;
                            if (newNode.id == selectedNode) {
                                option.selected = true;
                            }
                            node.appendChild(option);
                        });
                    });
                    alert('Berhasil disimpan');
                })
                .catch(error => console.error(error));
        }

        function hapus(id) {
            const cabang_id = id;
            fetch("/api/struktur-cabang/delete/" + cabang_id, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'Content-Type': 'application/json',
                    },
                }).then(response => response.json())
                .then(data => {
                    console.log(data);
                    window.location.reload();
                })
                .catch(error => console.error(error));
        }

        function tambah() {
            const nodes = document.getElementById('nodes');
            const selected_cabang = document.getElementById('tambah-select-cabang').value;

            const is_exists = document.getElementById(`node-${selected_cabang}`);
            if (is_exists) {
                alert('Cabang sudah ada');
                return;
            }

            const nodeTemplate = document.getElementById('node-template').content.cloneNode(true);
            nodeTemplate.querySelector('#nama').textContent = document.querySelector(
                '#tambah-select-cabang > option:checked').textContent;
            nodeTemplate.querySelector("#simpan").onclick = () => edit(selected_cabang);
            nodeTemplate.querySelector("#hapus").onclick = () => hapus(selected_cabang);

            const item = document.createElement('div');
            item.classList.add('flex', 'items-center', 'justify-between');
            item.id = `node-${selected_cabang}`;
            item.appendChild(nodeTemplate);

            nodes.appendChild(item);
        }
    </script>
</body>
