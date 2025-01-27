<div id="node-{{ $node['id'] }}" data-cabangid="{{ $node['cabang_id'] }}" class="border p-1 mb-2">
    <div class="flex mb-2 gap-2">
        <button class="node-cabang flex-grow border text-start"
            data-idnode="{{ $node['id'] }}">{{ $node['cabang']->nama }}</button>
        <div>
            <select class="node-list">
                <option value="">PUSAT</option>
                @foreach ($snodes as $snode)
                    <option value="{{ $snode['id'] }}" @if ($node['root_id'] == $snode['id']) selected @endif>
                        {{ $snode['cabang']->nama }}
                    </option>
                @endforeach
            </select>
            <button class="edit-node" data-idnode="{{ $node['id'] }}">Simpan</button>
            <button class="hapus-node" data-idnode="{{ $node['id'] }}"="">Hapus</button>
        </div>
    </div>
    <div id="child-of-{{ $node['id'] }}" class="ml-2 border p-1">
        <div class="modifier">
            <select class="cabang-list">
                @foreach ($cabangs as $cabang)
                    <option value="{{ $cabang->id }}">
                        {{ $cabang->nama }}
                    </option>
                @endforeach
            </select>
            <button class="tambah-node" data-idnode="{{ $node['id'] }}">Tambah</button>
        </div>
        @if (isset($node['children']))
            @foreach ($node['children'] as $child)
                @include('components.strukturtree', ['node' => $child])
            @endforeach
        @endif
    </div>
</div>
