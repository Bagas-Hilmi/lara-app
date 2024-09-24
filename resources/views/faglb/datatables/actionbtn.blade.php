<a href="{{ route('faglb.show', $row->id_head) }}" class="view btn btn-info btn-sm">View FAGLB</a>
<a href="{{ route('zlis1.show', $row->id_head) }}" class="view-zlis1 btn btn-secondary btn-sm">View ZLIS1</a>
<a href="{{ route('faglb.edit', $row->id_head) }}" class="edit btn btn-primary btn-sm">Update</a>
<a href="{{ route('faglb.destroy', $row->id_head) }}" class="delete btn btn-danger btn-sm"
   onclick="return confirm('Are you sure you want to delete this item?');">Delete</a>
   