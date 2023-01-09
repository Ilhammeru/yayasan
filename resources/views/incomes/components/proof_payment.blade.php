<div class="media-container" style="text-align: center;">
	@foreach ($images as $image)
		<div class="media-detail" style="margin-bottom: 10px; text-align: center;">
			<img src="{{ asset('storage/' . $image->path) }}" style="width: 180px; height: auto;" alt="">
		</div>
	@endforeach
</div>