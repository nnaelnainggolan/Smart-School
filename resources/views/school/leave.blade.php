@if(auth()->user()->role==='orang_tua')
<form method="POST" action="{{ route('school.leave.store') }}" enctype="multipart/form-data" class="dashboard-panel p-5 space-y-3">@csrf
<label class="block">Anak<select name="siswa_id" class="block border p-2 w-full">@foreach($children as $c)<option value="{{ $c->id }}">{{ $c->user->name }}</option>@endforeach</select></label>
<div class="grid sm:grid-cols-2 gap-3"><label>Mulai<input class="block border p-2 w-full" type="date" name="start_date" required value="{{ old('start_date') }}"></label><label>Sampai<input class="block border p-2 w-full" type="date" name="end_date" required value="{{ old('end_date') }}"></label></div>
<label class="block">Jenis<select name="type" class="block border p-2 w-full"><option>Izin</option><option>Sakit</option></select></label>
<label class="block">Alasan<textarea class="block border p-2 w-full" name="reason" required maxlength="2000">{{ old('reason') }}</textarea></label>
<label class="block">Lampiran (opsional, PDF/JPG/PNG, 5 MB)<input class="block w-full" type="file" name="attachment" accept=".pdf,.jpg,.jpeg,.png"></label><button class="bg-primary text-white p-3 rounded-lg" @disabled($children->isEmpty())>Kirim izin</button>
</form>@endif
@forelse($leaves as $l)<section class="dashboard-panel p-5 space-y-2"><h3 class="font-bold">{{ $l->siswa->user->name }} · {{ $l->type }}</h3><p>{{ $l->start_date }} – {{ $l->end_date }} · {{ $l->status }}</p><p>{{ $l->reason }}</p>@if($l->attachment)<a class="underline" href="{{ route('school.leave.attachment',$l) }}">Unduh lampiran</a>@endif<p>{{ $l->review_note }}</p>
@if($l->status==='pending' && in_array(auth()->user()->role,['admin','guru']))<form method="POST" action="{{ route('school.leave.review',$l) }}" class="space-y-2">@csrf @method('PUT')<label class="block">Catatan keputusan<input name="review_note" required maxlength="1000" class="block border p-2 w-full"></label><button name="status" value="approved" class="bg-primary text-white p-3 rounded-lg">Setujui</button><button name="status" value="rejected" class="border p-3 rounded-lg">Tolak</button></form>@endif</section>@empty<p>Belum ada pengajuan izin.</p>@endforelse
{{ $leaves->links() }}
