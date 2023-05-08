<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Thông báo về việc trả sách cho thành viên {{ $full_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous">
    </script>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 16px;
            color: #333;
        }

        .wrapper {
            display: flex;
            flex-direction: column;
            /* align-items: center;
            justify-content: center; */
        }

        .memberName,
        .email,
        .time {
            color: #e7650e;
            font-weight: bold;
        }

        .wrapper-content {
            /* display: block; */
            margin: 5px;
            color: #333;
            padding: 8px 10px;
        }

        #btnVerify {
            cursor: pointer;
        }

        .link {
            color: #fff;
            background-color: #e7650e;
            padding: 8px 8px;
            border: none;
            border-radius: 5px;
            width: 100px;
            text-align: center;
            margin: 20px auto;
            display: block;
            font-weight: bold;
        }

        footer {
            margin-top: 20px;
        }

        .copyright {
            padding-top: 20px;
            color: #f00;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="wrapper-content">
            <h2>Xin chào <span class="memberName">{{ $full_name }}</span>,</h2>
            <p class="email">Email: {{ $email }}</p>
            <p>
                Đây là danh sách những cuốn sách bạn đã mượn:
            </p>
            {{-- Danh sách sách mượn --}}
            <table class="table table-striped table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">STT</th>
                        <th scope="col">Tên sách</th>
                        <th scope="col">Số lượng mượn</th>
                        <th scope="col">Giá tiền mượn</th>
                        <th sccope="col">Thời gian mượn</th>
                        <th sccope="col">Thời gian dự kiến trả sách</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($books as $index => $book)
                        <tr>
                            <th scope="row">{{ $index + 1 }}</th>
                            <td>{{ $book['name'] }}</td>
                            <td>{{ $book['amount'] }}</td>
                            <td>{{ $book['payment'] }} {{ $book['unit'] }}</td>
                            <td>{{ $book['borrowed_at'] }}</td>
                            <td>{{ $book['estimated_returned_at'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <p style="font-weight: bold">
                Thời hạn trả của bạn là
                <span class="time">
                    {{ $estimated_returned_at }}
                </span>.
                Vui lòng trả sách đúng hạn. Xin cảm ơn!
            </p>
            <footer>
                Chào bạn,<br>
                <span class="copyright"><strong style="color:'red'">&copy; Thực tập sinh Gosu 2023</strong></span>
            </footer>
        </div>
    </div>
</body>

</html>
