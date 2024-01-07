<script>
    const laraExcelCraftFileImportRoute = "{{ route('lara_excel_craft.file_import') }}";
    const laraExcelCraftTableFetchRoute = "{{ route('lara_excel_craft.table_fetch') }}";
    const laraExcelCraftFileColumnsRoute = "{{ route('lara_excel_craft.file_columns', ['fileName' => '__#FILENAME_lec#__']) }}";
    const laraExcelCraftFileDataRoute = "{{ route('lara_excel_craft.file_data', ['fileName' => '__#FILENAME_lec#__']) }}";
    const laraExcelCraftExcelConfirmImportRoute = "{{ route('lara_excel_craft.excel_confirm_import') }}";
</script>
