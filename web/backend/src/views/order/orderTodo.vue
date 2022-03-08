<template>
  <div class="app-container">
    <!--  搜索  -->
    <div class="filter-container">
      <el-input v-model="listQuery.orderNo" placeholder="订单编号" style="width: 200px;" class="filter-item"
                @keyup.enter.native="handleFilter"/>

      <el-select v-model="listQuery.orderStatus" placeholder="订单状态" clearable class="filter-item" style="width: 130px"
                 value="">
        <el-option v-for="(val, key) in orderStatusMap" :key="key" :label="val" :value="key"/>
      </el-select>
      <el-button v-waves class="filter-item" type="primary" icon="el-icon-search" @click="handleFilter">
        搜索
      </el-button>

      <el-button v-waves :loading="downloadLoading" class="filter-item" type="primary" icon="el-icon-download"
                 @click="handleDownload">
        导出Excel
      </el-button>
    </div>

    <!--列表-->
    <el-table
      :key="tableKey"
      v-loading="listLoading"
      :data="list"
      border
      fit
      highlight-current-row
      style="width: 100%;"
      @sort-change="sortChange"
    >
      <el-table-column label="ID" prop="id" sortable="custom" align="center" width="80"
                       :class-name="getSortClass('id')">
        <template slot-scope="{row}">
          <span>{{ row.id }}</span>
        </template>
      </el-table-column>
      <el-table-column label="订单编号" align="center">
        <template slot-scope="{row}">
          <span>{{ row.order_sn }}</span>
        </template>
      </el-table-column>
      <el-table-column label="订单价格">
        <template slot-scope="{row}">
          <span>{{ row.order_price }}</span>
        </template>
      </el-table-column>
      <el-table-column label="是否付款" align="center">
        <template slot-scope="{row}">
          <span>{{ row.pay_time | payStatusFilter }}</span>
        </template>
      </el-table-column>
      <el-table-column label="订单状态" class-name="status-col">
        <template slot-scope="{row}">
          <span>{{ row.order_status | statusFilter }}</span>
        </template>
      </el-table-column>
      <el-table-column label="下单时间" class-name="status-col">
        <template slot-scope="{row}">
          <span>{{ row.add_time }}</span>
        </template>
      </el-table-column>
      <el-table-column label="商品总费用" class-name="status-col">
        <template slot-scope="{row}">
          <span>{{ row.goods_price }}</span>
        </template>
      </el-table-column>
      <el-table-column label="配送费用" class-name="status-col">
        <template slot-scope="{row}">
          <span>{{ row.freight_price }}</span>
        </template>
      </el-table-column>
      <el-table-column label="发货编号" class-name="status-col">
        <template slot-scope="{row}">
          <span>{{ row.ship_sn }}</span>
        </template>
      </el-table-column>
      <el-table-column label="用户订单留言" class-name="status-col">
        <template slot-scope="{row}">
          <span>{{ row.message }}</span>
        </template>
      </el-table-column>

      <el-table-column label="操作" align="center" width="300" class-name="small-padding fixed-width">
        <template slot-scope="{row,$index}">
          <el-button v-show="row.order_status == 101" type="primary" size="mini" @click="confirmOrder(row,index)">
            确认订单
          </el-button>
          <el-button v-if="row.status!='published'" size="mini" type="success"
                     @click="handleModifyStatus(row,'published')">
            详情
          </el-button>
          <el-button v-if="row.status!='published'" size="mini" type="success"
                     @click="handleShip(row)">
            查看物流
          </el-button>
          <el-button v-show="row.order_status != 103 && row.order_status != 102" size="mini" type="danger"
                     @click="handleDelete(row,$index)">
            取消
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <pagination v-show="total>0" :total="total" :page.sync="listQuery.page" :limit.sync="listQuery.limit"
                @pagination="getOrderPageList"/>
    <!-- 物流信息弹窗-->
    <el-dialog :visible.sync="dialogPvVisible" title="物流信息">
      <el-scrollbar style="height: 500px">
      <el-timeline>
        <el-timeline-item v-for="track in trackInfo.list" :key="index" :timestamp=" track.time " placement="top">
          <el-card>
            <h4>{{ track.status }}</h4>
            <p>{{ track.time }}</p>
          </el-card>
        </el-timeline-item>

      </el-timeline>
      <span slot="footer" class="dialog-footer">
        <el-button type="primary" @click="dialogPvVisible = false">确认</el-button>
      </span>
      </el-scrollbar>
    </el-dialog>

  </div>
</template>
<style>
.el-scrollbar__wrap {
  overflow-x: hidden;
}
</style>
<script>
import {getPageList, updateOrderStatus, getShipInfo} from '@/api/order'
import waves from '@/directive/waves' // waves directive
import {parseTime} from '@/utils'
import Pagination from '@/components/Pagination' // secondary package based on el-pagination
import orderLang from "@/config/lang/order"

export default {
  name: 'orderTodo',
  components: {Pagination},
  directives: {waves},
  filters: {
    statusFilter(status) {
      return orderLang.ORDER_STATUS_MAP[status]
    },
    /*是否付款*/
    payStatusFilter(time) {
      if (time) {
        return '是';
      }
      return '否';
    }
  },
  data() {
    return {
      tableKey: 0,
      list: null,
      trackInfo: {list:null},
      total: 0,
      listLoading: true,
      listQuery: {
        page: 1,
        limit: 20,
        importance: undefined,
        orderNo: undefined,
        orderStatus: undefined,
        sort: '+id'
      },
      // 订单状态
      updateOrderStatus: {
        confirm: 205,
        cancel: 102
      },
      orderStatusMap: orderLang.ORDER_STATUS_MAP,
      sortOptions: [{label: 'ID Ascending', key: '+id'}, {label: 'ID Descending', key: '-id'}],
      statusOptions: ['published', 'draft', 'deleted'],
      showReviewer: false,
      dialogFormVisible: false,
      dialogStatus: '',

      dialogPvVisible: false,
      pvData: [],
      rules: {
        type: [{required: true, message: 'type is required', trigger: 'change'}],
        timestamp: [{type: 'date', required: true, message: 'timestamp is required', trigger: 'change'}],
        title: [{required: true, message: 'title is required', trigger: 'blur'}]
      },
      downloadLoading: false
    }
  },
  created() {
    this.getOrderPageList()
  },
  methods: {
    // 获取订单列表
    getOrderPageList() {
      this.listLoading = true
      getPageList(this.listQuery).then(response => {
        this.list = response.data.rows
        this.total = response.data.total

        // Just to simulate the time of the request
        setTimeout(() => {
          this.listLoading = false
        }, 1.5 * 1000)
      })
    },
    // 确认订单
    confirmOrder(row, index) {
      let objThis = this;
      this.$confirm('订单状态改为确认状态, 是否继续?', '提示', {
        confirmButtonText: '确定',
        cancelButtonText: '取消',
        type: 'warning'
      }).then(() => {
        updateOrderStatus({id: row.id, status: this.updateOrderStatus.confirm}).then(function (data) {
          if (data.code === 0) {
            row.order_status = objThis.updateOrderStatus.confirm
            objThis.$notify({
              title: '提示',
              message: '操作成功',
              type: 'success',
              duration: 2000
            })
            objThis.list.splice(index, 1)
          }
        })
      }).catch(() => {

      })
    },
    // 查看物流
    handleShip(row){
      this.dialogPvVisible = true
      this.trackInfo.list = null;
      const objThis = this;
      getShipInfo({ship_sn: row.ship_sn}).then(response => {
        objThis.trackInfo.list = response.data.list
      })
    },
    handleFilter() {
      this.listQuery.page = 1
      this.getOrderPageList()
    },
    handleModifyStatus(row, status) {
      this.$message({
        message: '操作Success',
        type: 'success'
      })
      row.status = status
    },
    sortChange(data) {
      const {prop, order} = data
      if (prop === 'id') {
        this.sortByID(order)
      }
    },
    sortByID(order) {
      if (order === 'ascending') {
        this.listQuery.sort = '+id'
      } else {
        this.listQuery.sort = '-id'
      }
      this.handleFilter()
    },

    handleDelete(row, index) {
      const objThis = this
      updateOrderStatus({id: row.id, status: this.updateOrderStatus.cancel}).then(function (data) {
        if (data.code === 0) {
          row.order_status = objThis.updateOrderStatus.confirm
          objThis.$notify({
            title: '提示',
            message: '操作成功',
            type: 'success',
            duration: 2000
          })
          objThis.list.splice(index, 1)
        }
      })
    },
    handleDownload() {
      this.downloadLoading = true
      import('@/vendor/Export2Excel').then(excel => {
        const tHeader = ['ID', '订单编号']
        const filterVal = ['id', 'order_sn']
        const data = this.formatJson(filterVal)
        excel.export_json_to_excel({
          header: tHeader,
          data,
          filename: 'table-list'
        })
        this.downloadLoading = false
      })
    },
    formatJson(filterVal) {
      return this.list.map(v => filterVal.map(j => {
        if (j === 'timestamp') {
          return parseTime(v[j])
        } else {
          return v[j]
        }
      }))
    },
    getSortClass: function (key) {
      const sort = this.listQuery.sort
      return sort === `+${key}` ? 'ascending' : 'descending'
    }
  }
}
</script>
