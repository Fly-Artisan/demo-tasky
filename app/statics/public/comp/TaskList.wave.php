<table id="dtBasic" class="table" width="100%" style="{{ dtBasicStyle }}">
  <thead>
    <tr>
      <th class="th-sm">Task</th>
      <th class="th-sm">Date Added</th>
      <th class="th-sm">Status</th>
      <th class="th-sm text-info">Check</th>
      <th class="th-sm text-warning">Edit</th>
      <th class="th-sm red-text">Delete</th>
    </tr>
  </thead>
  <tbody>
    {:children}
  </tbody>
  <tfoot>
    <tr>
      <th class="th-sm">Task</th>
      <th class="th-sm">Date Added</th>
      <th class="th-sm">Status</th>
      <th class="th-sm text-info">Check</th>
      <th class="th-sm text-warning">Edit</th>
      <th class="th-sm red-text">Delete</th>
    </tr>
  </tfoot>
</table>