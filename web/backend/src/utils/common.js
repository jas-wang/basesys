
export function successDialog(objThis) {
  return objThis.$notify({
    title: '操作成功',
    message: 'Update Successfully',
    type: 'success',
    duration: 2000
  })
}
