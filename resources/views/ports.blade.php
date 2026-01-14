<x-app-layout>


@section('content')
    <h1>Welcome to SysAdmin Ports</h1>
    
 <style>
.table-custom {
    width: 60%;
    border-collapse: collapse;
    margin: 20px 0;
    font-size: 16px;
    text-align: left;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}
.table-custom th, .table-custom td {
    border: 1px solid #ddd;
    padding: 12px 15px;
}
.table-custom th {
    background-color: #f4f4f4;
    font-weight: bold;
}
.table-custom tr:hover {
    background-color: #f9f9f9;
}
.btn {
    padding: 6px 12px;
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
    border: none;
}
.btn-delete {
    background-color: #e74c3c;
    color: white;
}
.btn-delete:hover {
    background-color: #c0392b;
}
.btn-add {
    background-color: #2ecc71;
    color: white;
}
.btn-add:hover {
    background-color: #27ae60;
}
</style>

<table class="table-custom">
    <thead>
        <tr>
            <th>Port</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        @foreach($ports as $port)
            <tr>
                <td>{{ $port }}</td>
                <td>
                    <form action="{{ route('ports.delete', $port) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-delete">Delete</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

<h2 style="margin-top:20px;">Add New Port</h2>
<form action="{{ route('ports.add') }}" method="POST" style="margin-top:10px;">
    @csrf
    <input type="number" name="port" placeholder="Enter port number" style="padding:8px; border:1px solid #ddd; border-radius:4px;">
    <button type="submit" class="btn btn-add">Add</button>
</form>



@endsection

</x-app-layout>