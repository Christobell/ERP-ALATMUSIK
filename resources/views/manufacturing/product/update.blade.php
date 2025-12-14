  @extends('layouts.master')

  @section('title', 'Update Material')

  @section('content')

      <div class="pcoded-content">
          <div class="pcoded-inner-content">

              <div class="card">
                  <div class="card-header">
                      <h5>Tambah Material</h5>
                  </div>

                  <div class="card-block">
                      <form action="{{ route('product.update', $products->id) }}" method="POST" enctype="multipart/form-data">
                          @csrf
                          @method('PUT')
                          <div class="form-group row">
                              <label class="col-sm-2 col-form-label">Nama Material</label>
                              <div class="col-sm-10">
                                  <input type="text" name="name" class="form-control"
                                      placeholder="Masukkan nama material" value="{{ $products->name }}" required>
                              </div>
                          </div>

                          <div class="form-group row">
                              <label class="col-sm-2 col-form-label">Kode Material</label>
                              <div class="col-sm-10">
                                  <input type="text" name="code" class="form-control"
                                      placeholder="Contoh: MAT-001"value="{{ $products->code }}" required>
                              </div>
                          </div>

                          <div class="form-group row">
                              <label class="col-sm-2 col-form-label">Harga Material</label>
                              <div class="col-sm-10">
                                  <input type="number" name="price" class="form-control" placeholder="Masukkan harga"
                                      value="{{ $products->price }}" required>
                              </div>
                          </div>

                          <div class="form-group row">
                              <label class="col-sm-2 col-form-label">Stock</label>
                              <div class="col-sm-10">
                                  <input type="number" name="stock" class="form-control" value="{{ $products->stock }}"
                                      placeholder="Masukkan
                                          jumlah stok" required>
                              </div>
                          </div>
                          <div class="form-group row">
                              <label class="col-sm-2 col-form-label">Foto Material</label>
                              <div class="col-sm-10">

                                  {{-- Preview foto lama --}}
                                  @if ($products->image)
                                      <div class="mb-2">
                                          <img src="{{ asset('storage/' . $products->image) }}" width="120"
                                              class="rounded border">
                                      </div>
                                  @endif

                                  {{-- Input upload baru --}}
                                  <input type="file" name="image" class="form-control">

                                  <small class="text-muted">
                                      Kosongkan jika tidak ingin mengganti foto
                                  </small>
                              </div>
                          </div>


                          <div class="form-group row">
                              <div class="col-sm-10 offset-sm-2">
                                  <button type="submit" class="btn btn-primary">
                                      <i class="fa fa-save"></i> Simpan
                                  </button>
                              </div>
                          </div>

                      </form>
                  </div>
              </div>

          </div>
      </div>
  @endsection
