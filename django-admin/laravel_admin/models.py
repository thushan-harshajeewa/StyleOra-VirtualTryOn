from django.db import models
from django.conf import settings
import os


class Brand(models.Model):
    id = models.BigAutoField(primary_key=True)
    name = models.CharField(max_length=255)
    description = models.TextField(blank=True, null=True)
    brand_image=models.ImageField(upload_to='brand/')
    created_at = models.DateTimeField(blank=True, null=True)
    updated_at = models.DateTimeField(blank=True, null=True)

    def __str__(self):
        return self.name

    class Meta:
        db_table = 'brands'
        managed=False

    def save(self, *args, **kwargs):
        super().save(*args, **kwargs)  # Save the image file to the disk
        if self.brand_image:
            # Generate the full URL for the image
            self.brand_image = f"http://127.0.0.1:8080/{settings.MEDIA_URL}{self.brand_image.name}"
        super().save(*args, **kwargs)


class CartItems(models.Model):
    id = models.BigAutoField(primary_key=True)
    cart = models.ForeignKey('Cart', on_delete=models.CASCADE)
    product_item = models.ForeignKey('ProductItem', on_delete=models.CASCADE)
    quantity = models.IntegerField()
    created_at = models.DateTimeField(blank=True, null=True)
    updated_at = models.DateTimeField(blank=True, null=True)

    class Meta:
        db_table = 'cart_items'
        managed=False


class Cart(models.Model):
    id = models.BigAutoField(primary_key=True)
    customer = models.OneToOneField('Customer', on_delete=models.CASCADE)
    created_at = models.DateTimeField(blank=True, null=True)
    updated_at = models.DateTimeField(blank=True, null=True)

    class Meta:
        db_table = 'carts'
        managed=False


class Categorie(models.Model):
    id = models.BigAutoField(primary_key=True)
    name = models.CharField(max_length=255)
    description = models.TextField(blank=True, null=True)
    category_image=models.ImageField(upload_to='category/')
    created_at = models.DateTimeField(blank=True, null=True)
    updated_at = models.DateTimeField(blank=True, null=True)

    def __str__(self):
        return self.name

    class Meta:
        db_table = 'categories'
        managed=False

    def save(self, *args, **kwargs):
        super().save(*args, **kwargs)  # Save the image file to the disk
        if self.category_image:
            # Generate the full URL for the image
            self.category_image = f"http://127.0.0.1:8080/{settings.MEDIA_URL}{self.category_image.name}"
        super().save(*args, **kwargs)






class OrderItems(models.Model):
    id = models.BigAutoField(primary_key=True)
    order = models.ForeignKey('Order', on_delete=models.CASCADE)
    product_item = models.ForeignKey('ProductItem', on_delete=models.CASCADE)
    quantity = models.IntegerField()
    created_at = models.DateTimeField(blank=True, null=True)
    updated_at = models.DateTimeField(blank=True, null=True)

    class Meta:
        db_table = 'order_items'
        managed=False


class Order(models.Model):
    id = models.BigAutoField(primary_key=True)
    user = models.ForeignKey('Customer', on_delete=models.CASCADE)
    status = models.CharField(max_length=9)
    total_price = models.DecimalField(max_digits=10, decimal_places=2)
    shipping_address = models.CharField(max_length=255)
    created_at = models.DateTimeField(blank=True, null=True)
    updated_at = models.DateTimeField(blank=True, null=True)

    class Meta:
        db_table = 'orders'
        managed=False




class Payment(models.Model):
    id = models.BigAutoField(primary_key=True)
    order = models.ForeignKey(Order, on_delete=models.CASCADE)
    payment_method = models.CharField(max_length=255)
    transaction_id = models.CharField(unique=True, max_length=255)
    amount = models.DecimalField(max_digits=10, decimal_places=2)
    status = models.CharField(max_length=9)
    created_at = models.DateTimeField(blank=True, null=True)
    updated_at = models.DateTimeField(blank=True, null=True)

    class Meta:
        db_table = 'payments'
        managed=False


class Product(models.Model):
    PERSON_TYPE_GENTS = 'gents'
    PERSON_TYPE_LADIES = 'ladies'
    PERSON_TYPE_KIDS = 'kids'
    PERSON_TYPE_CHOICES = [
        (PERSON_TYPE_GENTS, 'Gents'),
        (PERSON_TYPE_LADIES, 'Ladies'),
        (PERSON_TYPE_KIDS, 'Kids')
    ]
    id = models.BigAutoField(primary_key=True)
    name = models.CharField(max_length=255)
    # main_image = models.CharField(max_length=255, blank=True, null=True)
    description = models.TextField(blank=True, null=True)
    person_type = models.CharField(max_length=10,choices=PERSON_TYPE_CHOICES,default=PERSON_TYPE_GENTS)
    brand = models.ForeignKey(Brand, on_delete=models.PROTECT,related_name='products')
    category = models.ForeignKey(Categorie, on_delete=models.PROTECT,related_name='products')
    # created_at = models.DateTimeField(blank=True, null=True)
    # updated_at = models.DateTimeField(blank=True, null=True)

    class Meta:
        db_table = 'products'
        managed=False



class ProductColor(models.Model):

    PRODUCT_COLOR_RED = 'red'
    PRODUCT_COLOR_GREEN = 'green'
    PRODUCT_COLOR_BLUE = 'blue'
    PRODUCT_COLOR_WHITE = 'white'
    PRODUCT_COLOR_BLACK = 'gray'
    PRODUCT_COLOR_TYPE = [
        (PRODUCT_COLOR_RED, 'Red'),
        (PRODUCT_COLOR_GREEN, 'Green'),
        (PRODUCT_COLOR_BLUE, 'Blue'),
        (PRODUCT_COLOR_WHITE, 'White'),
        (PRODUCT_COLOR_BLACK, 'Black'),
    ]
    id = models.BigAutoField(primary_key=True)
    product = models.ForeignKey(Product, on_delete=models.CASCADE,related_name='colors')
    color = models.CharField(max_length=255,choices=PRODUCT_COLOR_TYPE,null=True)
    is_display = models.BooleanField(default=False)
    product_picture = models.ImageField(upload_to='product_colors/')
    created_at = models.DateTimeField(blank=True, null=True)
    updated_at = models.DateTimeField(blank=True, null=True)

    class Meta:
        db_table = 'product_colors'
        managed=False

    def __str__(self):
        return f"{self.product.name} - {self.color}"
    
    def save(self, *args, **kwargs):
        #save for ensure the picture 
        super(ProductColor, self).save(*args, **kwargs)

       
        if self.product_picture:
            
            self.product_picture = f"http://127.0.0.1:8080/{settings.MEDIA_URL}{self.product_picture.name}"

        
        super(ProductColor, self).save(*args, **kwargs)



class ProductItem(models.Model):
    id = models.BigAutoField(primary_key=True)
    product_color = models.ForeignKey(ProductColor, on_delete=models.CASCADE, related_name='items')
    size = models.CharField(max_length=3)
    stock = models.PositiveIntegerField()
    price = models.DecimalField(max_digits=10, decimal_places=2)
    created_at = models.DateTimeField(blank=True, null=True)
    updated_at = models.DateTimeField(blank=True, null=True)

    class Meta:
        db_table = 'product_items'
        managed=False

   
    def __str__(self):
        return f"{self.product_color.product.name} - {self.product_color.color} - {self.size}"




class Reviews(models.Model):
    id = models.BigAutoField(primary_key=True)
    product = models.ForeignKey(Product, on_delete=models.CASCADE)
    user = models.ForeignKey('Customer', on_delete=models.CASCADE)
    rating = models.IntegerField()
    review = models.TextField(blank=True, null=True)
    created_at = models.DateTimeField(blank=True, null=True)
    updated_at = models.DateTimeField(blank=True, null=True)

    class Meta:
        db_table = 'reviews'
        managed=False


class Customer(models.Model):
    id = models.BigAutoField(primary_key=True)
    name = models.CharField(max_length=255)
    email = models.CharField(unique=True, max_length=255)
    email_verified_at = models.DateTimeField(blank=True, null=True)
    google_id = models.CharField(unique=True, max_length=255, blank=True, null=True)
    password = models.CharField(max_length=255)
    remember_token = models.CharField(max_length=100, blank=True, null=True)
    created_at = models.DateTimeField(blank=True, null=True)
    updated_at = models.DateTimeField(blank=True, null=True)
    

    class Meta:
        db_table = 'users'
        managed=False
