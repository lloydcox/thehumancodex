@extends('layouts.user')

@section('main')
    <section class="main-container">
        <section class="spacer is-large">
            <h5 class="section-name">Download Your Information</h5>
            <div class="card">
                <div class="card-body">
                    <p class="text-small-para">
                        You can download a copy of your THC information at any time. You can download a complete copy,
                        or you can select only the types of information and date ranges you want.
                        Downloading your information is a password-protected process that only you will have access to.
                        Once your copy has been created, it will be available for download for a few days.
                    </p>
                    <p class="text-small-para mt-3">If you'd like to view your information without downloading it, you can <a href="{{route('access_data')}}">Access your information</a>  at any time.</p>
                </div>
            </div>
            <form name="download_data" method="POST" action="{{ route('download_data_categories') }}">
                <div class="card">
                    <div class="card-header justify-content-between">
                        <h6 class="text-left line-height-none my-auto">Your Information <i class="fas fa-info-circle font"></i></h6>
                        <button type="submit" class="btn btn-outline-primary btn-sm">Download</button>
                    </div>
                    <div class="card-body">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="card mb-3 info-type-box">
                                    <div class="row no-gutters">
                                        <div class="col-md-1 text-center my-auto p-2">
                                            <img style="display:block; height:35px;" class="img-fluid" src="data:image/svg+xml;base64,
PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGlkPSJDYXBhXzEiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDUxMiA1MTIiIGhlaWdodD0iNTEyIiB2aWV3Qm94PSIwIDAgNTEyIDUxMiIgd2lkdGg9IjUxMiIgY2xhc3M9IiI+PGcgdHJhbnNmb3JtPSJtYXRyaXgoMSAwIDAgMSAwIDApIj48cGF0aCBkPSJtNDM2LjczMSAzNzYuNzMyYzQ4LjUzOS00OC41MzggNzUuMjY5LTExMi43MjMgNzUuMjY5LTE4MC43MzIgMC04LjI4NC02LjcxNi0xNS0xNS0xNWgtNDUuNTQ4Yy0yLjM0Ny0zMi4xOTMtMTIuMjU1LTYyLjcxNy0yOS4wNzktODkuNTE2IDEwLjU5Mi0xNy4zMjQgOC40MS00MC4zMTMtNi41NjctNTUuMjktMTUuMDQ1LTE1LjA0NC0zOC4xNzUtMTcuMTc2LTU1LjUyNi02LjQxOS0zMS4zMDQtMTkuNTE1LTY3LjExNy0yOS43NzUtMTA0LjI4LTI5Ljc3NS01MS44OTggMC0xMDAuNzk3IDE5Ljg5Mi0xMzcuNjg4IDU2LjAxLTM0LjA2MSAzMy4zNDYtNTQuMjYgNzcuMjc4LTU3Ljc2MyAxMjQuOTloLTQ1LjU0OWMtOC4yODQgMC0xNSA2LjcxNi0xNSAxNSAwIDY4LjAwOSAyNi43MyAxMzIuMTk0IDc1LjI2OSAxODAuNzMyIDM1LjA3NyAzNS4wNzggNzguMzMgNTguNzU4IDEyNS4yOSA2OS4xOTgtOS43MjcgMTAuMjQyLTE2LjI0MSAyMi42NTQtMTkuMDIxIDM2LjA3aC0xNi41MzhjLTguMjg0IDAtMTUgNi43MTYtMTUgMTVzNi43MTYgMTUgMTUgMTVoMTgxYzguMjg0IDAgMTUtNi43MTYgMTUtMTVzLTYuNzE2LTE1LTE1LTE1aC0xNi41MDljLTIuNzk1LTEzLjcxNi05LjM1NC0yNi4wNzMtMTguNTM2LTM1Ljk2NiA0Ny4xNDgtMTAuMzg1IDkwLjU3OC0zNC4xMDMgMTI1Ljc3Ni02OS4zMDJ6bS0xODAuNzMxIDQ1LjI2OGMtNjEuMDYzIDAtMTE2LjUzNy0yNC4zNTMtMTU3LjI0OS02My44NDQgOC4zOTQgNy4xOTcgMTguODI0IDEwLjgwOSAyOS4yNjIgMTAuODA5IDguNDEgMCAxNi44MTQtMi4zNDcgMjQuMTU1LTcuMDIgMzEuMjQ4IDE5LjY5OCA2Ni45MDQgMzAuMDU1IDEwMy44MzIgMzAuMDU1IDUyLjA4NiAwIDEwMS4yMzItMjAuNDYxIDEzOC4zODYtNTcuNjE0IDMzLjU2NS0zMy41NjUgNTMuNTAxLTc2LjkxOSA1Ny4wNC0xMjMuMzg2aDMwLjA2NGMtNy43NDggMTE3LjY0OS0xMDUuOTE0IDIxMS0yMjUuNDkgMjExem0tNjYuNDEtMjYzLjY1OWMxMC43MTgtMTkuNDA1IDI5LjMxNy0zMi43NjYgNTEuNDEtMzYuOTQ1djE0OS4wNzFjLTE0LjMxMy0yLjk0OS0yNy42MDktMTAuMDkzLTM4LjQzOS0yMC44NDEtNC45NzUtNC45MzctOS4xNTMtMTAuMzc4LTEyLjU0LTE2LjI0MyAxMi4wMzktOC4wODUgMTkuOTc5LTIxLjgyMyAxOS45NzktMzcuMzgzIDAtMTUuNzQzLTguMTMyLTI5LjYxNS0yMC40MS0zNy42NTl6bTE2Mi41NzctNTguNTA3YzguNzcyIDguNzcyIDIwLjI5NiAxMy4xNTggMzEuODE5IDEzLjE1OCA1LjAyNCAwIDEwLjA0OC0uODM1IDE0Ljg0NC0yLjUwMiAxNS4xOTQgMjUuNDUgMjMuMTcgNTQuNzQ1IDIzLjE3IDg1LjUxIDAgODYuNDc2LTY2LjQ3MSAxNTcuNzA3LTE1MSAxNjUuMzE1di02MC4zOTJjMjIuNDE0LTMuMjI3IDQzLjIyNy0xMy42NTggNTkuNzQ2LTMwLjE3NyA4LjkwMS04LjkwMSAxNi4wMDUtMTguOTcgMjEuMjIxLTMwLjAyNiAyMi40ODQtMi40ODIgNDAuMDMzLTIxLjU4NiA0MC4wMzMtNDQuNzIgMC0yMi45MDYtMTcuMjA4LTQxLjg1Ni0zOS4zNzMtNDQuNjM1LTcuNzcyLTE3LjE5NC0yMC4xMTYtMzEuODIzLTM2LjE4NC00Mi43MDQtMTMuNTc4LTkuMTk1LTI5LjMxNC0xNS4yMi00NS40NDMtMTcuNTU3di02MC40MzVjMjQuOTUzIDIuMjEzIDQ4Ljg5MiA5Ljk0MiA3MC40MjkgMjIuNzUzLTUuMzY4IDE1LjczOS0xLjc5MyAzMy44ODEgMTAuNzM4IDQ2LjQxMnptLTMwLjIwOCAxMzMuNTM2Yy0zLjM2MyA1LjgyNS03LjUwMiAxMS4yMzktMTIuNDI2IDE2LjE2NC0xMC44MTEgMTAuODEtMjQuMTQxIDE3Ljk5MS0zOC41MzMgMjAuOTQzdi0xNDguOTg4YzIxLjc2MiA0LjM1NCA0MC44MSAxNy45NTkgNTEuNDk2IDM2Ljc5Ny0xMi4zMjYgOC4wMzYtMjAuNDk2IDIxLjkzNS0yMC40OTYgMzcuNzE0IDAgMTUuNTUyIDcuOTMxIDI5LjI4NCAxOS45NTkgMzcuMzd6bS04MC45NTktMjAyLjcxOHY2MC4zNzljLTE3LjI5NSAyLjM1OS0zMy41NDQgOC43OS00Ny42MzMgMTguOTgtMTQuNzg3IDEwLjY5NS0yNi4zMTIgMjQuODQ2LTMzLjc5IDQxLjMyNy0yMi4yNjQgMi42ODgtMzkuNTc3IDIxLjY4NS0zOS41NzcgNDQuNjYyIDAgMjMuMTI1IDE3LjUzNiA0Mi4yMjQgNDAuMDA4IDQ0LjcxNyA1LjI1MiAxMS4xNDYgMTIuNDIzIDIxLjI3NCAyMS40MjEgMzAuMjAzIDE2LjUzNSAxNi40MDkgMzcuMjg1IDI2Ljc3NyA1OS41NzEgMjkuOTk5djYwLjM5NWMtMjQuODYtMi4yNjEtNDguNzU0LTEwLjEzMS03MC4yODgtMjMuMTczIDUuMTU1LTE1LjYzNyAxLjUzNy0zMy41NTktMTAuODc5LTQ1Ljk3NC0xMi40MzEtMTIuNDMtMzAuMzg0LTE2LjA0My00Ni4wMzUtMTAuODU4LTE1LjYwNy0yNS43NDUtMjMuNzk4LTU0Ljk4MS0yMy43OTgtODUuMzA5IDAtODguMDIgNjUuMjAyLTE1Ny45OTQgMTUxLTE2NS4zNDh6bS0yMTAuNDkgMTgwLjM0OGgzMC4wNjFjMi40MSAzMS42NTYgMTIuNDQzIDYxLjk2IDI5LjQ0OCA4OC44ODktMTAuNDMyIDE2LjQ0NS05LjE1NiAzOC4yMjEgMy44MjUgNTMuMzYtMzYuMjMtMzcuMzUxLTU5LjcwNC04Ny4xMy02My4zMzQtMTQyLjI0OXoiIGRhdGEtb3JpZ2luYWw9IiMwMDAwMDAiIGNsYXNzPSJhY3RpdmUtcGF0aCIgc3R5bGU9ImZpbGw6IzIwMjA5NSIgZGF0YS1vbGRfY29sb3I9IiMwMDAwMDAiPjwvcGF0aD48L2c+IDwvc3ZnPg==" />
                                        </div>
                                        <div class="col-md-10 text-left">
                                            <div class="card-body p-2">
                                                <h6>Moments</h6>
                                                <p class="card-text text-small-para"><small class="text-muted">Moments you have posted on THC</small></p>
                                            </div>
                                        </div>
                                        <div class="col-md-1 text-center my-auto mx-auto p-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="download_data_moments" name="download_data_moments" checked>
                                                <label class="custom-control-label" for="download_data_moments"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="card mb-3 info-type-box">
                                    <div class="row no-gutters">
                                        <div class="col-md-1 text-center my-auto p-2">
                                            <img style="display:block; height:35px;" class="img-fluid" src="data:image/svg+xml;base64,
PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGlkPSJDYXBhXzEiIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDUxMiA1MTIiIGhlaWdodD0iNTEyIiB2aWV3Qm94PSIwIDAgNTEyIDUxMiIgd2lkdGg9IjUxMiIgY2xhc3M9IiI+PGc+PHBhdGggZD0ibTQ2NyAzMWgtMzAyYy0yNC44MTMgMC00NSAyMC4xODctNDUgNDV2MTVoLTE1Yy0yNC44MTMgMC00NSAyMC4xODctNDUgNDV2MTVoLTE1Yy0yNC44MTMgMC00NSAyMC4xODctNDUgNDV2MTgwYzAgMjQuODEzIDIwLjE4NyA0NSA0NSA0NWgxNXY0NWMwIDUuNTMyIDMuMDQ1IDEwLjYxNSA3LjkyMiAxMy4yMjUgNC44NjUgMi42MDQgMTAuNzg0IDIuMzMyIDE1LjM5OC0uNzQ0bDg2LjIyMi01Ny40ODFoMTc3LjQ1OGMyNC44MTMgMCA0NS0yMC4xODcgNDUtNDV2LTE1aDE1YzI0LjgxMyAwIDQ1LTIwLjE4NyA0NS00NXYtMTVoMTVjMjQuODEzIDAgNDUtMjAuMTg3IDQ1LTQ1di0xODBjMC0yNC44MTMtMjAuMTg3LTQ1LTQ1LTQ1em0tMTA1IDM0NWMwIDguMjcxLTYuNzI5IDE1LTE1IDE1aC0xODJjLTIuOTYxIDAtNS44NTYuODc2LTguMzIgMi41MTlsLTY2LjY4IDQ0LjQ1M3YtMzEuOTcyYzAtOC4yODQtNi43MTYtMTUtMTUtMTVoLTMwYy04LjI3MSAwLTE1LTYuNzI5LTE1LTE1di0xODBjMC04LjI3MSA2LjcyOS0xNSAxNS0xNWgzMDJjOC4yNzEgMCAxNSA2LjcyOSAxNSAxNXptNjAtNjBjMCA4LjI3MS02LjcyOSAxNS0xNSAxNWgtMTV2LTEzNWMwLTI0LjgxMy0yMC4xODctNDUtNDUtNDVoLTI1N3YtMTVjMC04LjI3MSA2LjcyOS0xNSAxNS0xNWgzMDJjOC4yNzEgMCAxNSA2LjcyOSAxNSAxNXptNjAtNjBjMCA4LjI3MS02LjcyOSAxNS0xNSAxNWgtMTV2LTEzNWMwLTI0LjgxMy0yMC4xODctNDUtNDUtNDVoLTI1N3YtMTVjMC04LjI3MSA2LjcyOS0xNSAxNS0xNWgzMDJjOC4yNzEgMCAxNSA2LjcyOSAxNSAxNXoiIGRhdGEtb3JpZ2luYWw9IiMwMDAwMDAiIGNsYXNzPSJhY3RpdmUtcGF0aCIgc3R5bGU9ImZpbGw6IzIwMjA5NSIgZGF0YS1vbGRfY29sb3I9IiMwMDAwMDAiPjwvcGF0aD48cGF0aCBkPSJtMjg3IDI0MWgtMTgyYy04LjI4NCAwLTE1IDYuNzE2LTE1IDE1czYuNzE2IDE1IDE1IDE1aDE4MmM4LjI4NCAwIDE1LTYuNzE2IDE1LTE1cy02LjcxNi0xNS0xNS0xNXoiIGRhdGEtb3JpZ2luYWw9IiMwMDAwMDAiIGNsYXNzPSJhY3RpdmUtcGF0aCIgc3R5bGU9ImZpbGw6IzIwMjA5NSIgZGF0YS1vbGRfY29sb3I9IiMwMDAwMDAiPjwvcGF0aD48cGF0aCBkPSJtMjg3IDMwMWgtMTgyYy04LjI4NCAwLTE1IDYuNzE2LTE1IDE1czYuNzE2IDE1IDE1IDE1aDE4MmM4LjI4NCAwIDE1LTYuNzE2IDE1LTE1cy02LjcxNi0xNS0xNS0xNXoiIGRhdGEtb3JpZ2luYWw9IiMwMDAwMDAiIGNsYXNzPSJhY3RpdmUtcGF0aCIgc3R5bGU9ImZpbGw6IzIwMjA5NSIgZGF0YS1vbGRfY29sb3I9IiMwMDAwMDAiPjwvcGF0aD48L2c+IDwvc3ZnPg==" />
                                        </div>
                                        <div class="col-md-10 text-left">
                                            <div class="card-body p-2">
                                                <h6>Comments</h6>
                                                <p class="card-text text-small-para"><small class="text-muted">Comments you have posted on THC</small></p>
                                            </div>
                                        </div>
                                        <div class="col-md-1 text-center my-auto mx-auto p-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="download_data_comments" name="download_data_comments" checked>
                                                <label class="custom-control-label" for="download_data_comments"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="card mb-3 info-type-box">
                                    <div class="row no-gutters">
                                        <div class="col-md-1 text-center my-auto p-2">
                                            <img style="display:block; height:35px;" class="img-fluid" src="data:image/svg+xml;base64,
PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2ZXJzaW9uPSIxLjEiIGlkPSJDYXBhXzEiIHg9IjBweCIgeT0iMHB4IiB2aWV3Qm94PSIwIDAgNTEyIDUxMiIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNTEyIDUxMjsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSI1MTIiIGhlaWdodD0iNTEyIj48Zz48Zz4KCTxnPgoJCTxwYXRoIGQ9Ik0zNDYsMGMtNS41MjIsMC0xMCw0LjQ3OC0xMCwxMHY0NmMwLDUuNTIyLDQuNDc4LDEwLDEwLDEwYzUuNTIyLDAsMTAtNC40NzgsMTAtMTBWMTBDMzU2LDQuNDc4LDM1MS41MjIsMCwzNDYsMHoiIGRhdGEtb3JpZ2luYWw9IiMwMDAwMDAiIGNsYXNzPSJhY3RpdmUtcGF0aCIgc3R5bGU9ImZpbGw6IzIwMjA5NSIgZGF0YS1vbGRfY29sb3I9IiMwMDAwMDAiPjwvcGF0aD4KCTwvZz4KPC9nPjxnPgoJPGc+CgkJPHBhdGggZD0iTTExNiwzMTJjLTUuNTIsMC0xMCw0LjQ4LTEwLDEwYzAsNS41Miw0LjQ4LDEwLDEwLDEwczEwLTQuNDgsMTAtMTBDMTI2LDMxNi40OCwxMjEuNTIsMzEyLDExNiwzMTJ6IiBkYXRhLW9yaWdpbmFsPSIjMDAwMDAwIiBjbGFzcz0iYWN0aXZlLXBhdGgiIHN0eWxlPSJmaWxsOiMyMDIwOTUiIGRhdGEtb2xkX2NvbG9yPSIjMDAwMDAwIj48L3BhdGg+Cgk8L2c+CjwvZz48Zz4KCTxnPgoJCTxwYXRoIGQ9Ik00ODYsMjkyYzAtMjIuMDU2LTE3Ljk0NC00MC00MC00MGgtNzQuNjUyYzExLjU1Ny0yOS42MDMsMTYuMjM1LTY0LjQ0NywxNS4zMzMtOTUuNDY0ICAgIGMtMC4xNzItNS45MTktMC4zOTktOS4zNDItMC43MDYtMTEuNDk3QzM4NS40NTgsMTIzLjQzMiwzNjcuNzE2LDEwNiwzNDYsMTA2Yy0yMi4wNTYsMC00MCwxNy45NDQtNDAsNDB2MTAgICAgYzAsNjMuMjQ2LTgxLjIxNCw5OC43ODEtMTQyLjUwNCwxMTQuMDE5QzE1OC44NjIsMjU5LjQyNSwxNDguMjg0LDI1MiwxMzYsMjUySDM2Yy01LjUyMiwwLTEwLDQuNDc4LTEwLDEwdjI0MCAgICBjMCw1LjUyMiw0LjQ3OCwxMCwxMCwxMGgxMDBjMTIuMjAyLDAsMjIuNzE5LTcuMzI3LDI3LjQwMS0xNy44MDljNS4wNzUsMS4zMDIsOS43ODYsMi41MzIsMTQuMTM1LDMuNjY4ICAgIEMyMTIuNDUxLDUwNi45NzgsMjMxLjY4NCw1MTIsMjc4LjQ2LDUxMkgzODZjMjIuMDU2LDAsNDAtMTcuOTQ0LDQwLTQwYzAtOC4yMjQtMi40OTctMTUuODc0LTYuNzctMjIuMjM4ICAgIEM0MzQuNjc4LDQ0NC4zNTksNDQ2LDQyOS41OTksNDQ2LDQxMmMwLTguMTk0LTIuNDM2LTE1Ljg2OS02LjY5Ni0yMi4yNjJDNDU0LjY4NCwzODQuMzI2LDQ2NiwzNjkuNiw0NjYsMzUyICAgIGMwLTguMjI0LTIuNDk3LTE1Ljg3NC02Ljc3LTIyLjIzOEM0NzQuNjc4LDMyNC4zNTksNDg2LDMwOS41OTksNDg2LDI5MnogTTE0Niw0ODJjMCw1LjUxNC00LjQ4NiwxMC0xMCwxMEg0NlYyNzJoOTAgICAgYzUuNTE0LDAsMTAsNC40ODYsMTAsMTBWNDgyeiBNNDQ2LDMxMmMtMjEuNDQ5LDAtMjYuNTYyLDAtNTAsMGMtNS41MjIsMC0xMCw0LjQ3OC0xMCwxMGMwLDUuNTIyLDQuNDc4LDEwLDEwLDEwaDMwICAgIGMxMS4wMjgsMCwyMCw4Ljk3MiwyMCwyMGMwLDExLjA0Ni04Ljk1NCwyMC0yMCwyMGgtNTBjLTUuNTIyLDAtMTAsNC40NzgtMTAsMTBjMCw1LjUyMiw0LjQ3OCwxMCwxMCwxMGgzMCAgICBjMTAuODk3LDAsMjAsOC43MDUsMjAsMjBjMCwxMS4wNDYtOC45NTQsMjAtMjAsMjBjLTIxLjQ0OSwwLTI2LjU2MiwwLTUwLDBjLTUuNTIyLDAtMTAsNC40NzgtMTAsMTBjMCw1LjUyMiw0LjQ3OCwxMCwxMCwxMGgzMCAgICBjMTEuMDI4LDAsMjAsOC45NzIsMjAsMjBzLTguOTcyLDIwLTIwLDIwSDI3OC40NmMtNDQuMjA3LDAtNjEuNDczLTQuNTA5LTk1Ljg3LTEzLjQ5MmMtNS4wMzktMS4zMTYtMTAuNTY2LTIuNzU5LTE2LjU5LTQuMjk1ICAgIFYyOTAuMDI1QzIzOC4zNjgsMjcyLjc4NywzMjYsMjMwLjcxMSwzMjYsMTU2di0xMGMwLTExLjAyOCw4Ljk3Mi0yMCwyMC0yMGMxMS4wMDgsMCwxOS45NzksOC45NTgsMjAsMTkuOTY5VjE0NiAgICBjMCw0LjM0Niw1LjQyNCw1Ny42ODMtMTYuMzI2LDEwNkgzMTZjLTUuNTIyLDAtMTAsNC40NzgtMTAsMTBjMCw1LjUyMiw0LjQ3OCwxMCwxMCwxMGM2Ljk4MywwLDExOC42MDIsMCwxMzAsMCAgICBjMTEuMDI4LDAsMjAsOC45NzIsMjAsMjBDNDY2LDMwMy4wNDYsNDU3LjA0NiwzMTIsNDQ2LDMxMnoiIGRhdGEtb3JpZ2luYWw9IiMwMDAwMDAiIGNsYXNzPSJhY3RpdmUtcGF0aCIgc3R5bGU9ImZpbGw6IzIwMjA5NSIgZGF0YS1vbGRfY29sb3I9IiMwMDAwMDAiPjwvcGF0aD4KCTwvZz4KPC9nPjxnPgoJPGc+CgkJPHBhdGggZD0iTTExNiwzNTJjLTUuNTIyLDAtMTAsNC40NzgtMTAsMTB2ODBjMCw1LjUyMiw0LjQ3OCwxMCwxMCwxMGM1LjUyMiwwLDEwLTQuNDc4LDEwLTEwdi04MEMxMjYsMzU2LjQ3OCwxMjEuNTIyLDM1MiwxMTYsMzUyICAgIHoiIGRhdGEtb3JpZ2luYWw9IiMwMDAwMDAiIGNsYXNzPSJhY3RpdmUtcGF0aCIgc3R5bGU9ImZpbGw6IzIwMjA5NSIgZGF0YS1vbGRfY29sb3I9IiMwMDAwMDAiPjwvcGF0aD4KCTwvZz4KPC9nPjxnPgoJPGc+CgkJPHBhdGggZD0iTTI2NiwxMjZoLTQwYy01LjUyMiwwLTEwLDQuNDc4LTEwLDEwYzAsNS41MjIsNC40NzgsMTAsMTAsMTBoNDBjNS41MjIsMCwxMC00LjQ3OCwxMC0xMEMyNzYsMTMwLjQ3OCwyNzEuNTIyLDEyNiwyNjYsMTI2ICAgIHoiIGRhdGEtb3JpZ2luYWw9IiMwMDAwMDAiIGNsYXNzPSJhY3RpdmUtcGF0aCIgc3R5bGU9ImZpbGw6IzIwMjA5NSIgZGF0YS1vbGRfY29sb3I9IiMwMDAwMDAiPjwvcGF0aD4KCTwvZz4KPC9nPjxnPgoJPGc+CgkJPHBhdGggZD0iTTQ2NiwxMjZoLTQwYy01LjUyMiwwLTEwLDQuNDc4LTEwLDEwYzAsNS41MjIsNC40NzgsMTAsMTAsMTBoNDBjNS41MjIsMCwxMC00LjQ3OCwxMC0xMEM0NzYsMTMwLjQ3OCw0NzEuNTIyLDEyNiw0NjYsMTI2ICAgIHoiIGRhdGEtb3JpZ2luYWw9IiMwMDAwMDAiIGNsYXNzPSJhY3RpdmUtcGF0aCIgc3R5bGU9ImZpbGw6IzIwMjA5NSIgZGF0YS1vbGRfY29sb3I9IiMwMDAwMDAiPjwvcGF0aD4KCTwvZz4KPC9nPjxnPgoJPGc+CgkJPHBhdGggZD0iTTQzNy45MjEsNDQuMDc5Yy0zLjkwNi0zLjkwNC0xMC4yMzYtMy45MDQtMTQuMTQzLDBsLTI4LjI3OSwyOC4yNzljLTMuOTA1LDMuOTA1LTMuOTA1LDEwLjIzNywwLDE0LjE0MyAgICBjMy45MDcsMy45MDUsMTAuMjM2LDMuOTA0LDE0LjE0MywwbDI4LjI3OS0yOC4yNzlDNDQxLjgyNiw1NC4zMTcsNDQxLjgyNiw0Ny45ODUsNDM3LjkyMSw0NC4wNzl6IiBkYXRhLW9yaWdpbmFsPSIjMDAwMDAwIiBjbGFzcz0iYWN0aXZlLXBhdGgiIHN0eWxlPSJmaWxsOiMyMDIwOTUiIGRhdGEtb2xkX2NvbG9yPSIjMDAwMDAwIj48L3BhdGg+Cgk8L2c+CjwvZz48Zz4KCTxnPgoJCTxwYXRoIGQ9Ik0yOTYuNTAxLDcyLjM1OGwtMjguMjc5LTI4LjI3OWMtMy45MDYtMy45MDQtMTAuMjM2LTMuOTA0LTE0LjE0MywwYy0zLjkwNSwzLjkwNS0zLjkwNSwxMC4yMzcsMCwxNC4xNDNsMjguMjc5LDI4LjI3OSAgICBjMy45MDgsMy45MDUsMTAuMjM3LDMuOTA0LDE0LjE0MywwQzMwMC40MDYsODIuNTk2LDMwMC40MDYsNzYuMjY0LDI5Ni41MDEsNzIuMzU4eiIgZGF0YS1vcmlnaW5hbD0iIzAwMDAwMCIgY2xhc3M9ImFjdGl2ZS1wYXRoIiBzdHlsZT0iZmlsbDojMjAyMDk1IiBkYXRhLW9sZF9jb2xvcj0iIzAwMDAwMCI+PC9wYXRoPgoJPC9nPgo8L2c+PC9nPiA8L3N2Zz4=" />
                                        </div>
                                        <div class="col-md-10 text-left">
                                            <div class="card-body p-2">
                                                <h6>Kudos</h6>
                                                <p class="card-text text-small-para"><small class="text-muted">Kudos you have made on THC</small></p>
                                            </div>
                                        </div>
                                        <div class="col-md-1 text-center my-auto mx-auto p-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="download_data_kudos" name="download_data_kudos" checked>
                                                <label class="custom-control-label" for="download_data_kudos"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12 col-sm-12">
                                <div class="card mb-3 info-type-box">
                                    <div class="row no-gutters">
                                        <div class="col-md-1 text-center my-auto p-2">
                                            <img style="display:block; height:35px;" class="img-fluid" src="data:image/svg+xml;base64,
PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGhlaWdodD0iNTEyIiB2aWV3Qm94PSIwIDAgNTEyIDUxMiIgd2lkdGg9IjUxMiI+PGc+PHBhdGggZD0ibTUxMiAyNTZjMC0xNDEuNDg4MjgxLTExNC40OTYwOTQtMjU2LTI1Ni0yNTYtMTQxLjQ4ODI4MSAwLTI1NiAxMTQuNDk2MDk0LTI1NiAyNTYgMCAxNDAuMjM0Mzc1IDExMy41MzkwNjIgMjU2IDI1NiAyNTYgMTQxLjg3NSAwIDI1Ni0xMTUuMTIxMDk0IDI1Ni0yNTZ6bS0yNTYtMjI2YzEyNC42MTcxODggMCAyMjYgMTAxLjM4MjgxMiAyMjYgMjI2IDAgNDUuNTg1OTM4LTEzLjU1ODU5NCA4OS40MDIzNDQtMzguNzAzMTI1IDEyNi41MTU2MjUtMTAwLjk2ODc1LTEwOC42MDkzNzUtMjczLjQ0MTQwNi0xMDguODA0Njg3LTM3NC41OTM3NSAwLTI1LjE0NDUzMS0zNy4xMTMyODEtMzguNzAzMTI1LTgwLjkyOTY4Ny0zOC43MDMxMjUtMTI2LjUxNTYyNSAwLTEyNC42MTcxODggMTAxLjM4MjgxMi0yMjYgMjI2LTIyNnptLTE2OC41ODU5MzggMzc2LjVjODkuNzczNDM4LTEwMC42OTUzMTIgMjQ3LjQyMTg3Ni0xMDAuNjcxODc1IDMzNy4xNjc5NjkgMC05MC4wNzQyMTkgMTAwLjc3MzQzOC0yNDcuMDU0Njg3IDEwMC44MDQ2ODgtMzM3LjE2Nzk2OSAwem0wIDAiIGRhdGEtb3JpZ2luYWw9IiMwMDAwMDAiIGNsYXNzPSJhY3RpdmUtcGF0aCIgc3R5bGU9ImZpbGw6IzIwMjA5NSIgZGF0YS1vbGRfY29sb3I9IiMwMDAwMDAiPjwvcGF0aD48cGF0aCBkPSJtMjU2IDI3MWM0OS42MjUgMCA5MC00MC4zNzUgOTAtOTB2LTMwYzAtNDkuNjI1LTQwLjM3NS05MC05MC05MHMtOTAgNDAuMzc1LTkwIDkwdjMwYzAgNDkuNjI1IDQwLjM3NSA5MCA5MCA5MHptLTYwLTEyMGMwLTMzLjA4NTkzOCAyNi45MTQwNjItNjAgNjAtNjBzNjAgMjYuOTE0MDYyIDYwIDYwdjMwYzAgMzMuMDg1OTM4LTI2LjkxNDA2MiA2MC02MCA2MHMtNjAtMjYuOTE0MDYyLTYwLTYwem0wIDAiIGRhdGEtb3JpZ2luYWw9IiMwMDAwMDAiIGNsYXNzPSJhY3RpdmUtcGF0aCIgc3R5bGU9ImZpbGw6IzIwMjA5NSIgZGF0YS1vbGRfY29sb3I9IiMwMDAwMDAiPjwvcGF0aD48L2c+IDwvc3ZnPg==" />
                                        </div>
                                        <div class="col-md-10 text-left">
                                            <div class="card-body p-2">
                                                <h6>Profile Information</h6>
                                                <p class="card-text text-small-para"><small class="text-muted">Profile data on THC account</small></p>
                                            </div>
                                        </div>
                                        <div class="col-md-1 text-center my-auto mx-auto p-2">
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" class="custom-control-input"
                                                       id="download_data_profile" name="download_data_profile" checked>
                                                <label class="custom-control-label" for="download_data_profile"></label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </section>
    </section>
@endsection
