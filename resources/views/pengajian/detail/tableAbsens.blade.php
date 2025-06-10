 @foreach ($pengajian->Absens as $absen)
     <div class="col-md-6 col-12">
         <form action="{{ route('pengajian.update', $absen->id) }}" method="POST">
             @csrf
             @method('PATCH')
             <div class="card">
                 <div class="card-header bg-secondary rounded-top d-flex justify-content-between">
                     <div class="">
                         @php
                             $keterangan = json_decode($absen->keterangan);
                         @endphp
                         <h5 class="card-title text-light">{{ $absen->kelas->nama }}</h5>
                         <span class="text-light">Alpha : {{ $keterangan->alpha }} | Sakit :
                             {{ $keterangan->sakit }} | Izin :
                             {{ $keterangan->izin }} | Hadir : {{ $keterangan->hadir }}</span>
                     </div>
                     <div class="">
                         <button type="button" id="addRowButton" class="btn btn-info"
                             onclick="this.disabled=true; this.form.submit();">
                             Update
                         </button>
                     </div>

                 </div>
                 <div class="card-body">
                     <div class="table-responsive">
                         <table id="add-row" class="display table table-boredered table-hover">
                             <thead>
                                 <tr>
                                     <th>No</th>
                                     <th>Nama </th>
                                     <th style="width: 10%">Action</th>
                                 </tr>
                             </thead>
                             <tbody>
                                 @foreach (json_decode($absen->absen) as $item)
                                     <tr>
                                         <td>{{ $loop->iteration }}</td>
                                         <td>{{ $item->nama }}</td>
                                         <td>
                                             <div class="selectgroup w-100">
                                                 <label class="selectgroup-item">
                                                     <input type="radio" name="absensi[{{ $item->id_generus }}]"
                                                         value="A" class="selectgroup-input"
                                                         {{ $item->absen == 'alpha' ? 'checked' : '' }}>
                                                     <span class="selectgroup-button">Alpha</span>
                                                 </label>
                                                 <label class="selectgroup-item">
                                                     <input type="radio" name="absensi[{{ $item->id_generus }}]"
                                                         value="S" class="selectgroup-input"
                                                         {{ $item->absen == 'sakit' ? 'checked' : 'false' }}>
                                                     <span class="selectgroup-button">Sakit</span>
                                                 </label>
                                                 <label class="selectgroup-item">
                                                     <input type="radio" name="absensi[{{ $item->id_generus }}]"
                                                         value="I" class="selectgroup-input"
                                                         {{ $item->absen == 'izin' ? 'checked' : 'false' }}>
                                                     <span class="selectgroup-button">Izin</span>
                                                 </label>
                                                 <label class="selectgroup-item">
                                                     <input type="radio" name="absensi[{{ $item->id_generus }}]"
                                                         value="H" class="selectgroup-input"
                                                         {{ $item->absen == 'hadir' ? 'checked' : 'false' }}>
                                                     <span class="selectgroup-button">Hadir</span>
                                                 </label>
                                             </div>
                                         </td>
                                     </tr>
                                 @endforeach
                             </tbody>
                         </table>
                     </div>
                 </div>
             </div>
         </form>
     </div>
 @endforeach
