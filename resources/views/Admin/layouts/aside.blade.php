  
  <style>
.custom-dropdown {
  background-color: #435d78 !important; /* لون داكن مثل الموجود في الصورة */
  border: none;
  border-radius: 8px;
  min-width: 180px;
  padding: 5px 0;
}

/* تخصيص عناصر القائمة */
.custom-dropdown .dropdown-item {
  color: #fff !important;
  padding: 10px 20px;
  border-radius: 5px;
  transition: background-color 0.2s;
}

/* عند التمرير على العنصر */
.custom-dropdown .dropdown-item:hover {
  background-color: #2196f3 !important; /* أزرق فاتح */
  color: #fff !important;
}
</style>

    <aside class="admin-sidebar">
        <div class="sidebar-header">
            <h3>لوحة التحكم</h3>
        </div>
        
        <ul class="sidebar-menu">
            <li>
                <a href="{{route('admin.index')}}" class="{{ request()->routeIs('admin.index') ? 'active' : '' }}">
                    <i class="fas fa-home ml-2"></i>
                    الرئيسية
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ request()->routeIs('admin.secretary.index','admin.secretary.create','admin.secretary.edit','admin.secretary.show','admin.doctor.index','admin.doctor.create','admin.doctor.edit','admin.doctor.show') ? 'active' : '' }}" href="{{route('admin.secretary.index')}}" id="servicesDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-users ml-2"></i>الموظفين
                </a>
                <ul class="dropdown-menu custom-dropdown" aria-labelledby="servicesDropdown">
                    <li>
                        <a href="{{route('admin.doctor.index')}}" class="dropdown-item {{ request()->routeIs('admin.doctor.index','admin.doctor.create','admin.doctor.edit','admin.doctor.show') ? 'active' : '' }}">
                            <i class="fa-solid fa-user-tie ml-2"></i>الأطباء
                        </a>
                    </li> 
                    <li>
                        <a href="{{route('admin.secretary.index')}}" class="dropdown-item {{ request()->routeIs('admin.secretary.index','admin.secretary.create','admin.secretary.edit','admin.secretary.show') ? 'active' : '' }}">
                            <i class="fa-solid fa-user-tie ml-2"></i>    موظفين الاستقبال
                        </a>
                    </li>
                </ul> 
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ request()->routeIs('appointments.index','appointments.create','appointments.edit','appointments.show','sessions.index','sessions.create','sessions.edit','sessions.show','patientPayment.index','patientPayment.create','patientPayment.edit','patientPayment.show','admin.patients.index','admin.patients.create','admin.patients.edit','admin.patients.show','patients.statement') ? 'active' : '' }}" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-users ml-2"></i>المرضي
                </a>
                <ul class="dropdown-menu custom-dropdown" aria-labelledby="servicesDropdown">
                    <li>
                        <a href="{{route('appointments.index')}}" class="dropdown-item {{ request()->routeIs('appointments.index','appointments.create','appointments.edit','appointments.show') ? 'active' : '' }}">
                            <i class="fa-solid fa-user-tie ml-2"></i>المواعيد
                        </a>
                    </li> 
                    <li>
                        <a href="{{route('sessions.index')}}" class="dropdown-item {{ request()->routeIs('sessions.index','sessions.create','sessions.edit','sessions.show') ? 'active' : '' }}">
                            <i class="fa-solid fa-user-tie ml-2"></i> جلسات
                        </a>
                    </li>
                    <li>
                        <a href="{{route('patientPayment.index')}}" class="dropdown-item {{ request()->routeIs('patientPayment.index','patientPayment.create','patientPayment.edit','patientPayment.show') ? 'active' : '' }}">
                            <i class="fa-solid fa-user-tie ml-2"></i> دفعات من المريض
                        </a>
                    </li>
                    <li>
                        <a href="{{route('admin.patients.index')}}" class="dropdown-item {{ request()->routeIs('admin.patients.index','admin.patients.create','admin.patients.edit','admin.patients.show','patients.statement') ? 'active' : '' }}">
                            <i class="fa-solid fa-user-tie ml-2"></i>  تفاصيل المرضى 
                        </a>
                    </li>
                </ul> 
            </li>
            <li>
                <a href="{{route('admin.services.index')}}" class="{{request()->routeIs('admin.services.index','admin.services.create','admin.services.edit') ? 'active' : '' }}">
                    <i class="fas fa-store ml-2"></i>    الخدمات  
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ request()->routeIs('LaboratoryRequests.index','LaboratoryRequests.create','LaboratoryRequests.edit','LaboratoryRequests.show','laboratoryPurchases.index','laboratoryPurchases.create','laboratoryPurchases.edit','laboratoryPurchases.show','labpayment.index','labpayment.create','labpayment.edit','labpayment.show','laboratory.index','laboratory.create','laboratory.edit','laboratory.show','laboratory.statement') ? 'active' : '' }}" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-users ml-2"></i>المعامل
                </a>
                <ul class="dropdown-menu custom-dropdown" aria-labelledby="servicesDropdown">
                    <li>
                        <a href="{{route('LaboratoryRequests.index')}}" class="dropdown-item {{ request()->routeIs('LaboratoryRequests.index','LaboratoryRequests.create','LaboratoryRequests.edit','LaboratoryRequests.show') ? 'active' : '' }}">
                            <i class="fa-solid fa-user-tie ml-2"></i>طلبات المعمل
                        </a>
                    </li> 
                    <li>
                        <a href="{{route('laboratoryPurchases.index')}}" class="dropdown-item {{ request()->routeIs('laboratoryPurchases.index','laboratoryPurchases.create','laboratoryPurchases.edit','laboratoryPurchases.show') ? 'active' : '' }}">
                            <i class="fa-solid fa-user-tie ml-2"></i> فواتير الشراء
                        </a>
                    </li>
                    <li>
                        <a href="{{route('labpayment.index')}}" class="dropdown-item {{ request()->routeIs('labpayment.index','labpayment.create','labpayment.edit','labpayment.show') ? 'active' : '' }}">
                            <i class="fa-solid fa-user-tie ml-2"></i> الدفعات
                        </a>
                    </li>
                    <li>
                        <a href="{{route('laboratory.index')}}" class="dropdown-item {{ request()->routeIs('laboratory.index','laboratory.create','laboratory.edit','laboratory.show','laboratory.statement') ? 'active' : '' }}">
                            <i class="fa-solid fa-user-tie ml-2"></i>  تفاصيل المعامل 
                        </a>
                    </li>
                </ul> 
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ request()->routeIs('warehousePurchases.index','warehousePurchases.create','warehousePurchases.edit','warehousePurchases.show','warehousepayment.index','warehousepayment.create','warehousepayment.edit','warehousepayment.show','storehouse.index','storehouse.create','storehouse.edit','storehouse.show','storehouse.statement') ? 'active' : '' }}" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-users ml-2"></i>المستودعات
                </a>
                <ul class="dropdown-menu custom-dropdown" aria-labelledby="servicesDropdown">
                    <li>
                        <a href="{{route('warehousePurchases.index')}}" class="dropdown-item {{ request()->routeIs('warehousePurchases.index','warehousePurchases.create','warehousePurchases.edit','warehousePurchases.show') ? 'active' : '' }}">
                            <i class="fa-solid fa-user-tie ml-2"></i> فواتير الشراء
                        </a>
                    </li>
                    <li>
                        <a href="{{route('warehousepayment.index')}}" class="dropdown-item {{ request()->routeIs('warehousepayment.index','warehousepayment.create','warehousepayment.edit','warehousepayment.show') ? 'active' : '' }}">
                            <i class="fa-solid fa-user-tie ml-2"></i> الدفعات
                        </a>
                    </li>
                    <li>
                        <a href="{{route('storehouse.index')}}" class="dropdown-item {{ request()->routeIs('storehouse.index','storehouse.create','storehouse.edit','storehouse.show','storehouse.statement') ? 'active' : '' }}">
                            <i class="fa-solid fa-user-tie ml-2"></i>  تفاصيل المستودعات 
                        </a>
                    </li>
                </ul> 
            </li>
            <li>
                <a href="{{route('clinic_warehouse.index')}}" class="{{ request()->routeIs('clinic_warehouse.index') ? 'active' : '' }}">
                   <i class="fa-solid fa-user-doctor ml-2"></i>
                    مستودع العيادة 
                </a>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle {{ request()->routeIs('expenses.index','expenses.edit','expenses.create','Report.getReport','Report.getCashBox','Report.getCardBox','Report.patientsDebtsSummary') ? 'active' : '' }}" href="#" id="servicesDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-users ml-2"></i>المالية
                </a>
                <ul class="dropdown-menu custom-dropdown" aria-labelledby="servicesDropdown">
                    <li>
                        <a href="{{route('Report.getReport')}}" class="dropdown-item {{ request()->routeIs('Report.getReport') ? 'active' : '' }}">
                            <i class="fa-solid fa-user-tie ml-2"></i>تقارير
                        </a>
                    </li>
                    <li>
                        <a href="{{route('expenses.index')}}" class="dropdown-item {{ request()->routeIs('expenses.index','expenses.edit','expenses.create') ? 'active' : '' }}">
                            <i class="fa-solid fa-user-tie ml-2"></i>المصاريف
                        </a>
                    </li>
                    <li>
                        <a href="{{route('Report.getCashBox')}}" class="dropdown-item {{ request()->routeIs('Report.getCashBox') ? 'active' : '' }}">
                            <i class="fa-solid fa-user-tie ml-2"></i>الصندوق
                        </a>
                    </li>
                    <li>
                        <a href="{{route('Report.getCardBox')}}" class="dropdown-item {{ request()->routeIs('Report.getCardBox') ? 'active' : '' }}">
                            <i class="fa-solid fa-user-tie ml-2"></i>حساب البنك
                        </a>
                    </li>
                    <li>
                        <a href="{{route('Report.patientsDebtsSummary')}}" class="dropdown-item {{ request()->routeIs('Report.patientsDebtsSummary') ? 'active' : '' }}">
                            <i class="fa-solid fa-user-tie ml-2"></i>ديون المرضى 
                        </a>
                    </li>
                </ul> 
            </li>     
            <li>
                <a href="#" class="">
                    <i class="fas fa-cog ml-2"></i>
                    الإعدادات
                </a>
            </li>
        </ul>
    </aside>