<x-guest-layout>

    <div class="container position-sticky z-index-sticky top-0">
        <div class="row">
            <div class="col-12">
                <x-guest.sidenav-guest />
            </div>
        </div>
    </div>
    <main class="main-content  mt-0">
        <section>
            <div class="page-header min-vh-100">
                <div class="container">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="position-absolute w-40 top-0 start-0 h-100 d-md-block d-none">
                                <div class="oblique-image position-absolute d-flex fixed-top ms-auto h-100 z-index-0 bg-cover me-n8"
                                    style="background-image:url('../assets/img/image-sign-up.jpg')">
                                    <div class="my-auto text-start max-width-350 ms-7">
                                        <h1 class="mt-3 text-white font-weight-bolder">Mulai <br> perjalanan baru Anda.</h1>
                                        <p class="text-white text-lg mt-4 mb-4">Daftar sebagai peminjam arsip untuk mengakses sistem arsip.</p>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-group d-flex">
                                                <a href="javascript:;" class="avatar avatar-sm rounded-circle"
                                                    data-bs-toggle="tooltip" data-original-title="Jessica Rowland">
                                                    <img alt="Image placeholder" src="../assets/img/team-3.jpg"
                                                        class="">
                                                </a>
                                                <a href="javascript:;" class="avatar avatar-sm rounded-circle"
                                                    data-bs-toggle="tooltip" data-original-title="Audrey Love">
                                                    <img alt="Image placeholder" src="../assets/img/team-4.jpg"
                                                        class="rounded-circle">
                                                </a>
                                                <a href="javascript:;" class="avatar avatar-sm rounded-circle"
                                                    data-bs-toggle="tooltip" data-original-title="Michael Lewis">
                                                    <img alt="Image placeholder" src="../assets/img/marie.jpg"
                                                        class="rounded-circle">
                                                </a>
                                                <a href="javascript:;" class="avatar avatar-sm rounded-circle"
                                                    data-bs-toggle="tooltip" data-original-title="Audrey Love">
                                                    <img alt="Image placeholder" src="../assets/img/team-1.jpg"
                                                        class="rounded-circle">
                                                </a>
                                            </div>
                                            <p class="font-weight-bold text-white text-sm mb-0 ms-2">Bergabung dengan pengguna lainnya
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-start position-absolute fixed-bottom ms-7">
                                        <h6 class="text-white text-sm mb-5">Copyright © 2022 Corporate UI Design System
                                            by Creative Tim.</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 d-flex flex-column mx-auto">
                            <div class="card card-plain mt-8">
                                <div class="card-header pb-0 text-left bg-transparent">
                                    <h3 class="font-weight-black text-dark display-6">Daftar Akun</h3>
                                    <p class="mb-0">Silahkan isi data diri Anda untuk mendaftar sebagai peminjam.</p>
                                </div>
                                <div class="card-body">
                                    <form role="form" method="POST" action="sign-up">
                                        @csrf
                                        <label>Nama Lengkap</label>
                                        <div class="mb-3">
                                            <input type="text" id="name" name="name" class="form-control"
                                                placeholder="Masukkan nama lengkap" value="{{old("name")}}" aria-label="Name"
                                                aria-describedby="name-addon">
                                            @error('name')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <label>Email</label>
                                        <div class="mb-3">
                                            <input type="email" id="email" name="email" class="form-control"
                                                placeholder="Masukkan alamat email" value="{{old("email")}}" aria-label="Email"
                                                aria-describedby="email-addon">
                                            @error('email')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <label>Nomor Telepon</label>
                                        <div class="mb-3">
                                            <input type="text" id="phone" name="phone" class="form-control"
                                                placeholder="Masukkan nomor telepon" value="{{old("phone")}}" aria-label="Phone"
                                                aria-describedby="phone-addon">
                                            @error('phone')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <label>Seksi/Departemen</label>
                                        <div class="mb-3">
                                            <select id="department" name="department" class="form-select">
                                                <option value="" disabled selected>Pilih Seksi/Departemen</option>
                                                <option value="Seksi Kesejahteraan Sosial" {{ old('department') == 'Seksi Kesejahteraan Sosial' ? 'selected' : '' }}>Seksi Kesejahteraan Sosial</option>
                                                <option value="Seksi Pemberdayaan Masyarakat" {{ old('department') == 'Seksi Pemberdayaan Masyarakat' ? 'selected' : '' }}>Seksi Pemberdayaan Masyarakat</option>
                                                <option value="Seksi Pemerintahan" {{ old('department') == 'Seksi Pemerintahan' ? 'selected' : '' }}>Seksi Pemerintahan</option>
                                                <option value="Seksi Ekonomi Pembangunan dan Lingkungan Hidup" {{ old('department') == 'Seksi Ekonomi Pembangunan dan Lingkungan Hidup' ? 'selected' : '' }}>Seksi Ekonomi Pembangunan dan Lingkungan Hidup</option>
                                                <option value="Seksi Ketentraman/Ketertiban" {{ old('department') == 'Seksi Ketentraman/Ketertiban' ? 'selected' : '' }}>Seksi Ketentraman/Ketertiban</option>
                                                <option value="Sekretariat Kepegawaian dan Umum" {{ old('department') == 'Sekretariat Kepegawaian dan Umum' ? 'selected' : '' }}>Sekretariat Kepegawaian dan Umum</option>
                                                <option value="Sekretariat Program Keuangan" {{ old('department') == 'Sekretariat Program Keuangan' ? 'selected' : '' }}>Sekretariat Program Keuangan</option>
                                            </select>
                                            @error('department')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <label>Password</label>
                                        <div class="mb-3">
                                            <input type="password" id="password" name="password" class="form-control"
                                                placeholder="Buat password" aria-label="Password"
                                                aria-describedby="password-addon">
                                            @error('password')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <label>Konfirmasi Password</label>
                                        <div class="mb-3">
                                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                                                placeholder="Konfirmasi password" aria-label="Password Confirmation"
                                                aria-describedby="password-confirmation-addon">
                                            @error('password_confirmation')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-check form-check-info text-left mb-0">
                                            <input class="form-check-input" type="checkbox" name="terms"
                                                id="terms" required>
                                            <label class="font-weight-normal text-dark mb-0" for="terms">
                                                Saya menyetujui <a href="javascript:;"
                                                    class="text-dark font-weight-bold">Syarat dan Ketentuan</a>.
                                            </label>
                                            @error('terms')
                                                <span class="text-danger text-sm">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-dark w-100 mt-4 mb-3">Daftar</button>
                                        </div>
                                    </form>
                                </div>
                                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                                    <p class="mb-4 text-xs mx-auto">
                                        Sudah memiliki akun?
                                        <a href="{{ route('sign-in') }}" class="text-dark font-weight-bold">Masuk</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>

</x-guest-layout>