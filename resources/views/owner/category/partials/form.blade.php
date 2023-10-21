<div class="mb-3">
    <label for="name" class="form-label">Name</label>
    <input type="text" class="form-control" id="name" name="name" value="{{ old('name'), optional($category ?? null)->name }}"/>
</div>
<div class="mb-3">
    <label for="name" class="form-label">Status</label>
    <select class="form-select" name="status">
        <option value="" hidden>Select status</option>
        <option value="1">Active</option>
        <option value="2">In Atcive</option>
    </select>
</div>