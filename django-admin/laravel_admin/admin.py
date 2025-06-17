from django.contrib import admin
from django import forms
from django.contrib.auth.hashers import make_password
from .models import Customer,Order,OrderItems,Product, Brand, Categorie, ProductColor, ProductItem,Payment
import nested_admin
from django.utils.html import format_html

class UsersAdminForm(forms.ModelForm):
    class Meta:
        model = Customer
        fields = '__all__'

    def clean_password(self):
        password = self.cleaned_data.get('password')
        if password and not password.startswith('pbkdf2_'):  # Avoid re-hashing already hashed passwords
            return make_password(password)
        return password

@admin.register(Customer)
class UsersAdmin(admin.ModelAdmin):
    form = UsersAdminForm
    list_display = ['id', 'name', 'email']  # Adjust fields as needed
    search_fields = ['name', 'email']




@admin.register(Brand)
class BrandsAdmin(admin.ModelAdmin):
    fields = ('name', 'description', 'brand_image', 'thumbnail')
    list_display = ( 'name', 'description','thumbnail')
    readonly_fields=['thumbnail']
    search_fields = ('name',)
    ordering = ('name',)

    def thumbnail(self, obj):
        if obj.brand_image:
            return format_html('<img src="{}" style="width: 75px; height: auto;" />', obj.brand_image)
        return "No Image"

    thumbnail.short_description = "Thumbnail" 


@admin.register(Categorie)
class CategoriesAdmin(admin.ModelAdmin):
    fields = ('name', 'description', 'category_image','thumbnail')
    list_display = ( 'name', 'description','thumbnail')
    readonly_fields=['thumbnail']
    search_fields = ('name',)
    ordering = ('name',)

    def thumbnail(self, obj):
        if obj.category_image:
            return format_html('<img src="{}" style="width: 75px; height: auto;" />', obj.category_image.name)
        return "No Image"

    thumbnail.short_description = "Thumbnail" 



class ProductItemInline(nested_admin.NestedTabularInline):
    model = ProductItem
    extra = 1  # Add one empty row by default
    fields = ('size', 'stock', 'price')


class ProductColorInline(nested_admin.NestedTabularInline):
    model = ProductColor
    extra = 1
    fields = ('color', 'is_display', 'product_picture', 'thumbnail')  # Include the thumbnail field
    inlines = [ProductItemInline]
    readonly_fields = ('thumbnail',)  # Make the thumbnail field read-only
    

    def thumbnail(self, obj):
        if obj.product_picture:
            return format_html('<img src="{}" style="width: 75px; height: auto;" />', obj.product_picture)
        return "No Image"

    thumbnail.short_description = "Thumbnail"  # Nest ProductItemInline under ProductColorInline

    



@admin.register(Product)
class ProductAdmin(nested_admin.NestedModelAdmin):
    list_display = ('name', 'brand', 'category', 'person_type')
    search_fields = ('name',)
    inlines = [ProductColorInline]
    


class OrderItemsInline(admin.TabularInline):
    model = OrderItems
    extra = 1  # Show one empty row by default


@admin.register(Order)
class OrdersAdmin(admin.ModelAdmin):
    list_display = ('id', 'user', 'status', 'total_price', 'shipping_address', 'created_at', 'updated_at')
    search_fields = ('id', 'customer__name', 'status')
    list_filter = ('status', 'created_at')
    ordering = ('-created_at',)
    inlines = [OrderItemsInline]

@admin.register(Payment)
class PaymentAdmin(admin.ModelAdmin):
    list_display = ('id', 'order', 'payment_method', 'transaction_id', 'amount', 'status', 'created_at', 'updated_at')
    search_fields = ('order__id', 'transaction_id', 'payment_method')
    list_filter = ('status', 'payment_method', 'created_at')
    ordering = ('-created_at',)




# @admin.register(ProductColor)
# class ProductColorsAdmin(admin.ModelAdmin):
#     list_display = ('id', 'product', 'color', 'product_picture', 'created_at', 'updated_at')
#     search_fields = ('color', 'product__name')
#     ordering = ('product', 'color')


# @admin.register(ProductItem)
# class ProductItemsAdmin(admin.ModelAdmin):
#     list_display = ('id', 'product_color', 'size', 'stock', 'price', 'created_at', 'updated_at')
#     list_filter = ('product_color__color', 'size')
#     search_fields = ('product_color__product__name',)
#     ordering = ('product_color', 'size')


# @admin.register(Product)
# class ProductsAdmin(admin.ModelAdmin):
#     list_display = ('id', 'name', 'brand', 'category', 'person_type')
#     list_filter = ('brand', 'category', 'person_type')
#     search_fields = ('name',)
#     ordering = ('name',)



