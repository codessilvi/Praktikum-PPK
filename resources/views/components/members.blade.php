<!-- resources/views/components/members.blade.php -->
<div style="border: 1px solid #ccc; padding: 15px; margin-bottom: 20px; border-radius: 5px;">
    <h3>Anggota Kolaborasi</h3>
    
    <!-- Tampilkan pesan error/sukses dari Controller -->
    @if(session('success')) <p style="color: green;">{{ session('success') }}</p> @endif
    @if(session('error')) <p style="color: red;">{{ session('error') }}</p> @endif

    <!-- Form Tambah Anggota -->
    <form action="/lists/{{ $list->id }}/members" method="POST" style="margin-bottom: 15px;">
        @csrf
        <input type="email" name="email" placeholder="Masukkan email teman..." required style="padding: 5px; width: 60%;">
        <button type="submit" style="padding: 5px 10px; background-color: #008CBA; color: white; border: none; border-radius: 3px;">Tambah Anggota</button>
    </form>

    <!-- Daftar Anggota yang sudah gabung -->
    <ul>
        @foreach($list->members as $member)
            <li>
                {{ $member->name }} ({{ $member->email }})
                <!-- Tombol Hapus Anggota -->
                <form action="/lists/{{ $list->id }}/members/{{ $member->id }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" style="color: red; border: none; background: none; cursor: pointer; text-decoration: underline;">Hapus</button>
                </form>
            </li>
        @endforeach
    </ul>
</div>