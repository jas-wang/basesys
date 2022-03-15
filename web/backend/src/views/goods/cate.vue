<template>
  <div>

    <!-- 卡片视图区域 -->
    <el-card class="box-card">
      <!--  搜索  -->
      <div class="filter-container">
        <el-input
          v-model="listQuery.cateName"
          placeholder="分类名称"
          style="width: 200px;"
          class="filter-item"
          @keyup.enter.native="searchForm"
        />

        <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="searchForm">
          搜索
        </el-button>

      </div>
      <!-- 添加分类按钮 -->
      <el-button type="primary" class="addbnt" @click="addCate">添加分类</el-button>
      <!-- table区域 -->
      <tree-table :data="cateList" :columns="columns" :selection-type="false" :expand-type="false" :show-index="true" index-text="#" border>
        <!-- 排序列区域 -->
        <template slot="rank" slot-scope="scope">
          <el-tag v-if="scope.row.level === 'L1'">一级</el-tag>
          <el-tag v-else-if="scope.row.level === 'L2'" type="success">二级</el-tag>
          <el-tag v-else type="warning">三级</el-tag>
        </template>
        <template slot="operation" slot-scope="scope">
          <el-button type="primary" icon="el-icon-edit" size="mini" @click="editCate(scope.row)">修改</el-button>
          <el-button type="danger" icon="el-icon-delete" size="mini" @click="deleteCate(scope.row.id)">删除</el-button>
          <!-- <pre>{{scope.row}}</pre> -->
        </template>
      </tree-table>
      <!-- 分页区域 -->
      <pagination
        v-show="total>0"
        :total="total"
        :page.sync="listQuery.page"
        :limit.sync="listQuery.limit"
        @pagination="getCateList"
      />
    </el-card>
    <!-- 添加分类的对话框区域 -->
    <el-dialog
      title="添加商品分类"
      :visible.sync="addCateDialogVisible"
      width="50%"
      @close="addCateDialogClose"
    >
      <!-- 添加表单区域 -->
      <el-form ref="addCateFormRef" :model="addCateForm" :rules="addCateFormRules" label-width="150px">
        <el-form-item label="商品类别名称：" prop="name">
          <el-input v-model="addCateForm.name" />
        </el-form-item>
        <el-form-item label="父级分类：">
          <!-- 级别选择器区域 -->
          <!-- options用来指定数据源 -->
          <!-- props用来指定配置对象 -->
          <el-cascader
            v-model="selectedKeys"
            :options="parentCateList"
            :props="cascaderProps"
            expand-trigger="hover"
            clearable
            change-on-select
            @change="parentCateChange"
          />
        </el-form-item>
      </el-form>
      <!-- 添加底部区域 -->
      <span slot="footer" class="dialog-footer">
        <el-button @click="addCateDialogVisible = false">取 消</el-button>
        <el-button type="primary" @click="saveAddCate">确 定</el-button>
      </span>
    </el-dialog>
    <!-- 商品类别编辑 -->
    <el-dialog
      title="商品类别编辑"
      :visible.sync="editCateDialogVisible"
      width="50%"
      @close="editCateDialogClose"
    >
      <el-form ref="cateEditFormRef" :model="cateEditForm" :rules="cateEditFormRules" label-width="80px">
        <el-form-item label="商品名称" prop="name">
          <el-input v-model="cateEditForm.name" />
        </el-form-item>
        <el-form-item label="分类：">
          <!-- 级别选择器区域 -->
          <!-- options用来指定数据源 -->
          <!-- props用来指定配置对象 -->
          <el-cascader
            v-model="selectedKeys"
            :options="parentCateList"
            :props="cascaderProps"
            expand-trigger="hover"
            clearable
            change-on-select
            @change="parentCateChange"
          />
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="editCateDialogVisible = false">取 消</el-button>
        <el-button type="primary" @click="saveCateEdit">确 定</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script>
import Pagination from '@/components/Pagination'
import waves from '@/directive/waves' // waves directive
import { getCate, getCateList, getParentCateList } from '@/api/goods' // secondary package based on el-pagination
export default {
  components: { Pagination },
  directives: { waves },
  data() {
    return {
      listLoading: true,
      listQuery: {
        page: 1,
        limit: 20,
        cateName: '',
        sort: '+id'
      },
      // 商品类别列表数据
      cateList: [],
      // 获取商品类别列表参数对象
      queryInfo: {
        // 查询参数
        type: 3,
        // 当前页码
        pagenum: 1,
        // 每页显示条数
        pagesize: 5
      },
      // 查询到的数据总数
      total: 0,
      // table表中的数据列
      columns: [
        {
          label: '类别名称',
          prop: 'name'
        },
        {
          label: '描述',
          prop: 'desc'
        },
        {
          label: '排序',
          type: 'template',
          template: 'rank'
        },
        {
          label: '操作',
          type: 'template',
          template: 'operation'
        }
      ],
      // 是否打开添加商品分类对话框
      addCateDialogVisible: false,
      // 获取添加商品类表单数据
      addCateForm: {
        name: '',
        // 父级分类ID
        pid: 0,
        // 分类等级，默认添加等级为一级
        level: 0
      },
      // 添加商品类别表单验证规则
      addCateFormRules: {
        name: [
          { required: true, message: '请输入商品类别名称', trigger: 'blur' },
          { min: 2, max: 10, message: '长度在 2 到 10 个字符', trigger: 'blur' }
        ]
      },
      // 获取父级分类列表的数据
      parentCateList: [],
      // 指定几杯选择器的配置对象
      cascaderProps: {
        value: 'id',
        label: 'name',
        children: 'children'
      },
      // 选中的父级ID数据
      selectedKeys: [],
      // 编辑获取的数据
      cateEditForm: {
        name: ''
      },
      // 编辑对话框的表单验证规则
      cateEditFormRules: {
        name: [
          { required: true, message: '请输入商品类别名称', trigger: 'blur' },
          { min: 2, max: 10, message: '长度在 2 到 10 个字符', trigger: 'blur' }
        ]
      },
      // 判断编辑对话框是否打开
      editCateDialogVisible: false
    }
  },
  created() {
    this.getCateList()
  },
  methods: {
    searchForm() {
      this.listQuery.page = 1
      this.getCateList()
    },
    // 获取订单列表
    getCateList() {
      this.listLoading = true
      getCateList(this.listQuery).then(response => {
        this.cateList = response.data.rows
        this.total = response.data.total
        this.listLoading = false
      })
    },
    // 添加商品类别按钮事件
    addCate() {
      this.getParentCateList()
      this.addCateDialogVisible = true
    },
    // 获取订单列表
    getParentCateList() {
      this.listLoading = true
      getParentCateList(this.listQuery).then(response => {
        this.parentCateList = response.data
        this.listLoading = false
      })
    },
    // 添加商品分类中选择项发生变化，触发的函数
    parentCateChange() {
      // console.log(this.selectedKeys)
      // 如果数组的length大于0,证明选中父级类别
      // 反之则，没有选择任何父级类别
      if (this.selectedKeys.length > 0) {
        // 父级分类Id
        this.addCateForm.pid = this.selectedKeys[this.selectedKeys.length - 1]
        // 当前类别分类等级
        this.addCateForm.level = this.selectedKeys.length
      } else {
        this.addCateForm.pid = 0
        this.addCateForm.level = 0
      }
    },
    // 点击添加商品类别对话框里的确定按钮保存数据
    saveAddCate() {
      this.$refs.addCateFormRef.validate(async valid => {
        if (!valid) return this.$message.error('请输入正确格式！')
        const { data: res } = await this.$http.post('categories', this.addCateForm)
        if (res.meta.status !== 201) return this.$message.error('添加商品类别失败！')
        this.$message.success('添加商品类别成功！')
        this.getCateList()
        this.addCateDialogVisible = false
        console.log(res)
      })
    },
    // 关闭添加商品类别对话框重置里面的数据
    addCateDialogClose() {
      this.$refs.addCateFormRef.resetFields()
      this.selectedKeys = []
      this.addCateForm.level = 0
      this.addCateForm.pid = 0
    },
    // 点击商品类别删除按钮
    async deleteCate(id) {
      const result = await this.$confirm('此操作将永久删除该类别, 是否继续?', '提示',
        {
          confirmButtonText: '确定',
          cancelButtonText: '取消',
          type: 'warning'
        }).catch(error => error)
      // 如果点取消则返回一个cancel字符串
      // 如果点确定则返回一个confirm字符串
      // console.log(result)
      if (result !== 'confirm') return this.$message.info('已取消删除')
      // 发送一个网咯请求，删除该类别
      const { data: res } = await this.$http.delete('categories/' + id)
      if (res.meta.status !== 200) return this.$message.error('删除该类别失败!')
      this.$message.success('删除成功！')
      // 删除成功之后，刷新类别列表
      this.getCateList()
    },
    // 点击修改按钮
    async editCate(row) {
      if (!this.parentCateList.length) {
        this.getParentCateList()
      }
      this.cateEditForm = row
      const objThis = this
      this.listLoading = true
      getCate({ id: row.id }).then(response => {
        objThis.selectedKeys = response.data
        objThis.editCateDialogVisible = true
      })
    },
    // 关闭编辑按钮，数据重置
    editCateDialogClose() {
      this.$refs.cateEditFormRef.resetFields()
    },
    // 点击编辑按钮里的确定按钮保存数据
    saveCateEdit() {
      this.$refs.cateEditFormRef.validate(async valid => {
        if (!valid) return this.$message.error('请按规则输入')
        const { data: res } = await this.$http.put('categories/' + this.cateEditForm.cat_id, {
          name: this.cateEditForm.name
        })
        if (res.meta.status !== 200) return this.$message.error('修改类别失败！')
        this.$message.success('修改类别信息成功！')
        this.getCateList()
        this.editCateDialogVisible = false
      })
    }
  }
}
</script>

<style scoped>
.addbnt{
  margin-bottom: 10px;
}
.el-cascader{
  width: 100%;
}
</style>
