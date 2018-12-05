<!DOCTYPE html>
<html>
    <head>
        <style>
            body{
                size: landscape;
            }
            table {
                font-family: arial, sans-serif;
                font-size: 12px;
                border-collapse: collapse;
                width: 100%;
            }

            td, th {
                border: 1px solid #dddddd;
                text-align: left;
                padding: 3px;
            }

            tr:nth-child(even) {
                background-color: #dddddd;
            }
        </style>
    </head>
    <body>

        <h2>Data Aktivitas Sebaran</h2>        
        <h5>Status : Approve</h5>        

        <table>
            <tr>
                <th>No</th>
                <th>Pembuat</th>
                <th>Satker</th>
                <th>Wilayah</th>
                <th>Tanggal</th>
                <th>Media</th>
                <th>Paket</th>
                <th>Anggaran</th>
                <th>Sasaran</th>
                <th>Jumlah Sebaran</th>                
            </tr>
            @foreach($data as $key => $value)
            <tr>    
                <td>{{$value['No']}}</td>
                <td>{{$value['Pembuat']}}</td>
                <td>{{$value['Satker']}}</td>
                <td>{{$value['Wilayah']}}</td>
                <td>{{$value['Tanggal']}}</td>
                <td>{{$value['Media']}}</td>
                <td>{{$value['Paket']}}</td>
                <td>{{$value['Anggaran']}}</td>
                <td>{{$value['Sasaran']}}</td>
                <td>{{$value['Jumlah_Sebaran']}}</td>                
            </tr>
            @endforeach  
        </table>
</body>
</html>