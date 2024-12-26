<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }

        .container {
            margin: 20px;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            padding: 8px;
        }

        table th {
            background-color: #f2f2f2;
        }

        .text-end {
            text-align: right;
        }

        .address {
            font-size: 11px;
        }

        .total-details {
            width: 50%;
            text-align: right;
        }

        .bank-logo {
            width: 10px;
            height: auto;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .container {
                margin: 0;
                padding: 20px;
                border: none;
                border-radius: 0;
            }

            .total-section {
                margin-top: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <table>
            <tr>
                <td>
                    <!-- Informasi Kaifacraft -->
                    <img src="dist/logo.gif" alt="logo_kaifacraft" width="150px">
                    <p>Sentra kerajinan tangan unggulan</p>
                    <address class="address">
                        Jl. Cikuya RT.03/07 Desa/Kec. Rajapolah<br>
                        Kab. Tasikmalaya - Jawa Barat<br>
                        WhatsApp: 089639152588, 081779200583<br>
                        Instagram: @kaifa_craft, @kaifacraft<br>
                    </address>
                </td>
                <td class="text-end">
                    <p><strong>Tenggat Waktu:</strong>28 desember 2024</p>
                    <p><strong>Nota No:</strong> INV/202498121</p>
                    <p><strong>Kepada:</strong>Dhafa</p>
                </td>
            </tr>
        </table>

        <!-- Tabel Detail Produk -->
        <table style="border: 1px solid #ddd">
            <thead>
                <tr>
                    <th>Nama Barang</th>
                    <th class="text-center">Qty</th>
                    <th class="text-end">Harga Satuan</th>
                    <th class="text-end">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td></td>
                    <td class="text-center"></td>
                    <td class="text-end">
                    </td>
                    <td class="text-end">
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Section Total -->
        <table>
            <tr>
                <td>
                    <p>Pembayaran via transfer:</p>
                    <div>
                        <span>
                            <img src="data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KCjwhLS0gVXBsb2FkZWQgdG86IFNWRyBSZXBvLCB3d3cuc3ZncmVwby5jb20sIEdlbmVyYXRvcjogU1ZHIFJlcG8gTWl4ZXIgVG9vbHMgLS0+Cjxzdmcgd2lkdGg9IjYwcHgiIGhlaWdodD0iNjBweCIgdmlld0JveD0iNC40NDUgLTE1NS42NjggNDYyLjM3IDQ2Mi4zNyIKICAgIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CgogICAgPHBhdGggZmlsbD0iIzAwNUZBRiIgc3Ryb2tlPSIjMDA1RkFGIiBkPSJNMjU1LjE2OSA3Mi4wNGMyOS4yMTctMTMuMjUyIDMzLjUzOC01Ni40OTctOC4wMjEtNTYuNDk3SDE5OS4wMmwtMTcuNzg2IDM0Ljg3NWg4LjM3bC0yMS4yNzQgODMuMzUxaDU1LjhjNDAuNzA5LjM0OCA1OS4wMTctNDcuNTg4IDMxLjAzOS02MS43Mjl6bS0zMS4wMzkgMzEuMzg4aC0xMy45NWw1LjIzMS0xOC44MzJoMTMuMjUyYzE1LjY5NCAzLjEzOCA1LjkzIDE5LjUyOS00LjUzMyAxOC44MzJ6bTguMzctNDAuMTA3aC0xMS44NTdsMy40ODctMTYuMzkxaDEzLjI1MmMxMi44MDUgMy4xMzkgMS43NDUgMTcuMDg5LTQuODgyIDE2LjM5MXoiLz4KCiAgICA8cGF0aCBmaWxsPSIjMDA1RkFGIiBzdHJva2U9IiMwMDVGQUYiIGQ9Ik0zNjkuNDE4IDYwLjA4M2wxOC4yNzEtMzIuMjU0LTI0LjcyMy0xMi44OTYtMy4yMjUgMy45OGMtMTMuMzM3LTcuMDYtODMuMTc2IDIuNjg4LTg3LjU0OSA3MS41ODEgMi42MTQgNTcuNzkzIDY3LjM3MyA0Ny4zOTcgNzMuNzI1IDQzLjczbDE3LjgxMS0zNi44MTljLTE3LjQ0NCAxMS40OS01My4xNjIgMTcuMDA2LTU0LjY3My0xNy41MDkgMy4xMzMtMjguNDYyIDM2LjczNy0zOS4wOTEgNjAuMzYzLTE5LjgxM3pNNDYwLjU5MiAxNS41NDNoLTU2LjE2OWwtMTcuODU0IDMzLjgzMWg5LjE3MmwtNDIuMzMgODQuMzk1aDQwLjk3M2w4LjQxMi0yMC4zNTZoMjUuMTUxbC42MjcgMjAuMzU2aDM3LjcxN2wtNS42OTktMTE4LjIyNnptLTQ2LjQwMSA3MC40NjNsMTMuNzU1LTMyLjgzMy42MjcgMzIuODMzaC0xNC4zODJ6Ii8+CgogICAgPGcgZmlsbD0iIzAwNUZBRiI+CgogICAgICAgIDxwYXRoIGQ9Ik0xMDkuODI2IDEyOC4xMjVsMi4xODItLjYzMy0yLjM5My0zLjMwN3pNNzQuMDA0IDEyNy41NjJsLS40MjIgMi45NTZjMS4wODYtLjEwNiAyLjQyMy44MyAyLjk1Ni0xLjI2Ny0uMDQtMS42MzQtMS4yODQtMS43MjQtMi41MzQtMS42ODl6TTkxLjM4OCAxMjcuNDkyYy0uMTgxLS44MDUtLjg1My0xLjM5OC0yLjUzNC0uODQ0bC41NjMgMy4wMjVjMS4zODEtLjMwNiAyLjA5MS0xLjEyMyAxLjk3MS0yLjE4MXpNNTIuMTE3IDEyNS4wMTZsLS42NzIgMi42ODhjMS4wOC4wNDUgMi4zNDUuOTIxIDMuMDYyLS42NzIuMDQ4LS45OTguMDg5LTEuOTkyLTIuMzktMi4wMTZ6TTg5LjkxIDEzMS45MjZsLjQ5MyAzLjA5N2MxLjE1My4wMjkgMi4yMDUtLjY2NCAxLjktMi4xMTEtLjI5NC0xLjM5Mi0xLjQ2My0xLjE3Mi0yLjM5My0uOTg2eiIvPgoKICAgICAgICA8cGF0aCBkPSJNMTM1LjY3OCAxNy4xMzdDOTguNDExLTEuODQ4IDUzLjkwNC0xLjMyIDE4LjM5NiAxNi4zMDljLTE4LjMxIDMyLjIzMy0xOC44OTMgNzcuMzMyIDAgMTE3LjI3NiAzOS4xMTkgMTkuNzk2IDgyLjM4NSAxOC40OTIgMTE3LjU1Ny44MjggMTYuNTMtMzMuOTEzIDE5LjI2LTc0LjcyMS0uMjc1LTExNy4yNzZ6bS02Mi45NCA5Ni4yNDhsLTIuMjA5LjAyNWMtLjU0Ny02LjEwNC0yLjAwNi0xMS4yMzQtNS4xMzYtMTUuMTZDNTEuMDQgODAuMjUxIDIxLjcgOTMuODAyIDI5LjIzOSA2My4yMTNjMy45Mi0xNS42MzggNDguNjMzLTI0Ljg2MSA0My40OTkgNTAuMTcyek0yOS4yOTIgODMuMTczYzMuMTM0IDYuMzc5IDIyLjc2OSA3LjQyNCAyOS41ODUgMTIuMzA3IDEwLjgxMiA3Ljc0NSA5LjI2IDE3LjkzMyA5LjI2IDE3LjkzM2gtMi41NWMtNS4wMi0xNS4zODctMTkuMTA5LTMuMjA0LTMwLjM5NC03LjM0OC03LjA4NS0yLjUyLTEyLjIyOS0xMy40NDMtNS45MDEtMjIuODkyem0zNS43NzQgNTAuMTYxbDEuMDU2LTguMzc1IDEuOS4xNDEtLjg0NSA5LjIxOWMtLjg1MiA0LjA2MS02LjkwNyAzLjUxOC03LjY3MS0uMzUybC45ODUtOS41NzEgMi4yNTIuMzUzLS45ODUgOC4wMjJjLjEyNSAyLjY1NSAyLjc4OCAyLjQ5NiAzLjMwOC41NjN6bS0xMC4xMTEgMi4yODdsLTIuMTY2LS41MjNjLjI1NC0xLjc5IDIuMzc3LTUuNDMyLTEuODE5LTUuNDMybC0xLjE2OCA0Ljc2LTIuMzktLjQ0OCAyLjUzOS0xMS40MjdjNi4yMDYuNjc2IDYuNzQ5IDIuNDAzIDYuODcyIDQuMjU3LS4yNDUgMS40MzgtLjYyOCAyLjQ0Mi0xLjg2NyAyLjY4OCAxLjQ1IDEuMzE2LjAxNyA0LjA3NC0uMDAxIDYuMTI1em0tMTMuNzU3LTEyLjc5OWMtLjU5Ny43OTItMS43OTIgMy41NTktMS45NjUgNS43NjQtLjE4NCAyLjMzNyAyLjA2MSAxLjg0NSAyLjYyIDEuNTcyLjQ1MS0uMjE5Ljc4Ni0yLjA5Ni43ODYtMi4wOTZsLTEuNTcyLS41MjQuNTI0LTEuNTcxIDMuNTM4IDEuMTc5LTEuNzAzIDUuODk2LTEuNDQxLS4zOTQuMTMxLS45MTdjLTEuNzY0LjgwNC00Ljk5My4yMDEtNS4zNzItMS44MzQtLjM3OS0xLjYzNS43NjktNi41OTIgMi42Mi04LjkwOCAxLjc5OS0yLjI1MiA2LjE4Mi0uMDUxIDYuMjg5IDEuMzEuMTA2IDEuMzU2LS40MjkgMi44ODItLjQyOSAyLjg4MmwtMS42NS0uMzExcy4xMDEtLjQ5LjI0NS0xLjI2MmMuMTUyLS44MTgtMS45ODEtMS42MzctMi42MjEtLjc4NnptMzIuMTczIDkuNTk2bC0uNDIyIDUuMDY4LTEuNzU5LS4wNy42MzMtMTIuMDM1YzQuMzkzLjA5MSA2LjkxMS42OTkgNi44MjcgMy44MDEtLjI2MiA0LjIzOC0zLjY5NCAzLjI4NS01LjI3OSAzLjIzNnptNC4zNzktMTkuMDA2aC0yLjk0M2MtLjI2MS0xMC40MjIgMS43NDMtMjkuNDg1LTMuNzMzLTQzLjUxNC00LjE0OS0xMC42MzUtMTQuNjQ5LTE1LjQxOS0xNi45NzYtMjQuMDEtMy42ODQtMTMuNTk4IDIuNTg2LTI4LjMxMSAyMy42NTItMjkuNDQ0IDE4LjI2MyAxLjg3NSAyMy44NzQgMTUuOTc0IDIwLjc1OSAyOS4xMDEtNS4zNTYgMTMuMTI1LTEwLjcxMyAxMS4yMzktMTcuMzgyIDI0LjMxMy01LjQ0NyAxNC4wMTUtMy4zNzMgMzIuMzc2LTMuMzc3IDQzLjU1NHptMzIuNjM5IDE5LjI4OGwtMi4wNDEuNzA0LS42MzQtMTIuMTA1IDIuMzkzLS42MzMgNS45MTIgOS45OTMtMS42MTkgMS4wNTYtMS45NzEtMi44MTQtMi4yNTIuODQ0LjIxMiAyLjk1NXptLTIzLjgxMS0xOS4yODhoLTIuMzU0YzEuOTQtMjYuNzY2IDM1LjU3NS0yMC45NTEgMzguNjQ5LTMwLjIzOSA1LjMxNiA5LjIwOCAzLjQwNSAxNi45NzItMi4xNDYgMjAuOTUyLTEzLjc5NCA5Ljg5NC0yNy4wMjEtNy4zMjItMzQuMTQ5IDkuMjg3em0xMi40ODEgMTMuNTE4bDEuMDU2IDUuNTZjLjY1NyAxLjI4MSAxLjEwMyAxLjA0NCAyLjExMS45MTUgMS4xNjktLjg0Ni45MjUtMS42Ny43MDQtMi43NDVsMi4wNDEtLjI4MWMuNTMyIDQuMDI4LTEuMzgzIDQuNDM5LTIuODE1IDQuNzE1LTMuMDcxLjIyLTMuNjczLS4zNzgtNC41MDQtNC4zNjMtLjQ4NC0zLjQxMS0xLjMyLTcuMDEzIDEuNTQ4LTcuNjcxIDQuNzA4LS43MTkgNC40MyAxLjQgNC44NTYgMi44ODVsLTIuMDQxLjM1M2MtLjMyNi0xLjI3Ni0uNzk1LTEuNzgyLTEuODMtMS42ODgtLjk0My40MzMtMS4zMyAxLjEzNC0xLjEyNiAyLjMyem0tNC4yOTMgNS43N2MuMDk4IDEuNzUzLS40OTcgMi45MTctMS42MTkgMy4zMDhsLTQuNzg2Ljk4NS0xLjktMTEuNjEyYy45ODItLjE2IDUuNzk3LTEuOTI0IDYuOTY3IDEuMjY4LjU3NyAxLjk0OS0uMDIxIDIuNjQ4LS43MDQgMy41MTggMS4yOTUuNDE2IDEuNzMyIDEuNDI0IDIuMDQyIDIuNTMzem0tMTIuMjktNTguMjczYzEwLjY2Mi0zMC4wMiAzNS4wNTktMjQuNzE1IDQwLjQ3LTExLjIxMyA3LjQxMyAyNy4wOTMtMTYuNDU3IDE5LjY2My0zMS43NDcgMzAuNzczLTUuNzc5IDUuMDI2LTguODIgMTAuNzkyLTguODY1IDE5LjUzM2gtMi42MmMtLjE1Ny0xNy42MDYtLjQ0Mi0zMC4wNzMgMi43NjItMzkuMDkzeiIvPgoKICAgIDwvZz4KCjwvc3ZnPg==" alt="BCA Logo" class="bank-logo">
                        </span>: 6320 3530 82
                    </div>
                    <div>
                        <span>
                            <img src="data:image/svg+xml;base64,PHN2ZyB2ZXJzaW9uPSIxLjIiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgdmlld0JveD0iMCAwIDE1NjQgNTkyIiB3aWR0aD0iNjBweCIgaGVpZ2h0PSI2MHB4Ij4KCTx0aXRsZT5CUklfMjAyMC1zdmc8L3RpdGxlPgoJPHN0eWxlPgoJCS5zMCB7IGZpbGw6ICMwMDUyOWMgfSAKCTwvc3R5bGU+Cgk8ZyBpZD0ibGF5ZXIxIj4KCQk8ZyBpZD0iZzM4Ij4KCQkJPGcgaWQ9ImcxMzYiPgoJCQkJPHBhdGggaWQ9InBhdGgxMzgiIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xhc3M9InMwIiBkPSJtNTkxLjkgOTMuNGwtMC4yIDQwNi4xYzAgNTEuMS00MC41IDkyLjQtOTAuMyA5Mi40aC00MTEuNmMtNDkuMi0wLjctODguOC00MS44LTg4LjgtOTIuNHYtNDA2LjFjMC01MS4xIDQwLjQtOTIuNCA5MC4yLTkyLjRoNDEwLjVjNDkuOCAwIDkwLjIgNDEuMyA5MC4yIDkyLjR6bS0zNjEuOCAzNjkuMWMwLTIwLjctOC40LTM5LjctMjIuNC01NC4ybC0xMDguOS0xMTAuOCAxMTYuMS0xMTcuN2MxMy4zLTEzLjkgMjEuNS0zMi44IDIxLjUtNTMuOCAwLTQyLjItMzMuNC03OC4xLTc0LjctNzguMWgtNDUuNWMtMjYuNCAwLTQ2LjYgMjEuNS00Ny43IDQ4bC0wLjEgMjAuMnYzNzUuMWwwLjEgMy45YzAgMjcuNCAyMS45IDQ5LjggNDkuMSA0OS44bDIyLjQtMC4zYzQ5LjggMCA5MC4xLTM2LjggOTAuMS04Mi4xem0yNzEgNzQuOWwtMjM2LjctMjQxLjEgMTE0LTExNi41YzEzLjMtMTMuOSAyMS41LTMyLjggMjEuNS01My44IDAtNDIuMi0zMy41LTc4LjEtNzQuOC03OC4xaC00OS4yYzE0LjIgMjAuNCAyMyA0OC41IDIzIDgwLjIgMCA0MC0xNCA3NS4yLTM1LjIgOTUuMWwtNzAuOCA3My4xIDY5LjQgNzAuOGMyMC4xIDIyLjUgMzIuOSA1Ni44IDMyLjkgOTUgMCAzMS40LTIzLjUgODIuMS0yMy41IDgyLjFsMjAzIDAuMWM5LjQgMCAxOC44LTIuMiAyNi40LTYuOXptMjMuMy00MzkuNWMwLTI3LjUtMjIuMS00OS44LTQ5LjItNDkuOGwtMzYuMS0xLjFjMTQuMyAyMC40IDIzLjIgNDkuMiAyMy4yIDgxLjEgMCAzNC42LTEwLjUgNjUuNS0yNi45IDg2LjFsLTgwLjggODIgMTY5LjYgMTcyLjd6Ii8+CgkJCTwvZz4KCQkJPGcgaWQ9ImcxNDgiPgoJCQkJPHBhdGggaWQ9InBhdGgxNDIiIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xhc3M9InMwIiBkPSJtMTAwMC41IDMxOXExOS4xIDMyLjYgMTkuMSA3NC40IDAgMzEuMi0xMC4zIDU4LjUtMTAuMyAyNy4zLTMxLjUgNDcuOC0yMS4zIDIwLjYtNTMuNiAzMi4zLTMyLjMgMTEuNy03NS41IDExLjdoLTE5OC42di00OTYuMWgxODAuOXE0MS4xIDAgNzEuOSAxMC43IDMwLjkgMTAuNiA1MS41IDI5LjQgMjAuNSAxOC44IDMwLjUgNDMuOSA5LjkgMjUuMiA5LjkgNTQuMiAwIDE0LjktNC4zIDI4LTQuMiAxMy4xLTEwLjMgMjMuNy02IDEwLjctMTIuNyAxOC41LTYuOCA3LjgtMTEuNyAxMi43IDI1LjUgMTcuNyA0NC43IDUwLjN6bS0xNzIuNC0xODYuM2gtODUuOHYxMDloMTM4LjFxNy03IDE0LjEtMjAuNSA3LTEzLjQgNy0zMy44IDAtMjIuNi0xNi45LTM4LjhjLTQuNC00LjItOS45LTcuNS0xNi42LTEwLjEtMTAuNS0zLjgtMjMuOC01LjgtMzkuOS01Ljh6bS04NS44IDE5NC44djEyOS40aDEwMC42cTQ0LjUgMCA2My45LTE5IDE5LjUtMTkuMSAxOS41LTQ1LjljMC0xNy40LTUuNy0zMi40LTE3LjEtNDUuMXEtMTguMS0xOS40LTU1LjUtMTkuNHptNTYxLjggMjUuNWwxMzQgMTkwLjdoLTExMC4ybC0xMjkuNi0xODQuM2gtNTIuNHYxODQuM2gtOTR2LTQ5Ni4xaDE5NS40cTQzLjMgMC4xIDc2LjMgMTEuNCAzMi45IDExLjMgNTQuOSAzMS41IDIyIDIwLjIgMzMgNDguMiAxMSAyOCAxMSA2MS4zIDAgMjUuNS03LjEgNTAtNy4xIDI0LjQtMjIgNDQuNi0xNC45IDIwLjItMzcuMiAzNS40LTIyLjQgMTUuMy01Mi4xIDIzem0tMTU4LjItMjIwLjN2MTQwLjhoOTkuNXEyMS45IDAgMzcuNS02LjQgMTUuNS02LjMgMjUuNC0xNi42IDkuOS0xMC4yIDE0LjUtMjMuNiA0LjYtMTMuNCA0LjYtMjcuNSAwLTI4LjMtMjEuNi00Ny43Yy0xMC41LTkuNS0yNC4yLTE1LjQtNDEtMTgtNS4zLTAuNi0xMC45LTEtMTYuOC0xem00MTcuNi04NS4xdjQ5Ni4xaC05NS44di00OTYuMWgwLjhjMC45IDAgMS44IDAuMiAyLjggMC4ydi0wLjJ6Ii8+CgkJCTwvZz4KCQk8L2c+Cgk8L2c+Cjwvc3ZnPg=="alt="BRI Logo"
                                class="bank-logo">
                        </span>: 3466-01-035685-53-3
                    </div>
                    <p>a.n. <strong>Sandi Susandi</strong></p>
                </td>
                <td class="total-details">
                    <p><strong>Sub Total:</strong> </p>
                    <p><strong>Biaya Kirim:</strong> </p>
                    <p><strong>Down Payment (DP):</strong>
                    </p>
                    <p><strong>Total:</strong> </p>
                </td>
            </tr>
        </table>

        <p class="text-end">Hormat Kami,</p>
        <p class="text-end"><strong>Kaifacraft</strong></p>
        <hr>
        <div class="">
            <h3>Members :</h3>
            <img src="dist/members.svg" alt="members" width="100" height="auto">
        </div>
    </div>
</body>

</html>
