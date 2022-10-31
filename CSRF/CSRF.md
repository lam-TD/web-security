Cross-site request forgery (CSRF) vulnerabilities can be used to trick a user’s browser into performing an unwanted action on your site.


Các lỗ hổng bảo mật giả mạo yêu cầu trên nhiều trang web (CSRF - Cross-site request forgery) có thể được sử dụng để lừa trình duyệt của người dùng thực hiện một hành động không mong muốn trên trang web của bạn.

Cách phòng chống

Ngay cả khi các hành động chỉnh sửa bị hạn chế đối với các yêu cầu không phải GET, bạn cũng không được bảo vệ hoàn toàn. Yêu cầu ĐĂNG vẫn có thể được gửi đến trang web của bạn từ các tập lệnh và trang được lưu trữ trên các miền khác. Để đảm bảo rằng bạn chỉ xử lý các yêu cầu HTTP hợp lệ, bạn cần phải bao gồm mã thông báo bí mật và duy nhất với mỗi phản hồi HTTP và yêu cầu máy chủ xác minh mã thông báo đó khi nó được chuyển trở lại trong các yêu cầu tiếp theo sử dụng phương thức POST (hoặc bất kỳ phương pháp nào khác thực tế là ngoại trừ GET.)

Đây được gọi là mã thông báo chống giả mạo(`Anti-forgery tokens are typically`). Mỗi khi máy chủ của bạn hiển thị một trang thực hiện các hành động nhạy cảm, nó sẽ viết ra mã thông báo chống giả mạo trong trường biểu mẫu HTML ẩn. Mã thông báo này phải được bao gồm trong các lần gửi biểu mẫu hoặc lệnh gọi AJAX. Máy chủ phải xác thực mã thông báo khi nó được trả lại trong các yêu cầu tiếp theo và từ chối bất kỳ cuộc gọi nào có mã thông báo bị thiếu hoặc không hợp lệ.

Mã thông báo chống giả mạo thường là các số ngẫu nhiên được lưu trữ trong cookie hoặc trên máy chủ khi chúng được ghi vào trường ẩn. Máy chủ sẽ so sánh mã thông báo được đính kèm với yêu cầu gửi đến với giá trị được lưu trữ trong cookie. Nếu các giá trị giống nhau, máy chủ sẽ chấp nhận yêu cầu HTTP hợp lệ.

#### **Đảm bảo cookie được gửi với thuộc tính Cookie Samesite**

`Set-Cookie: CookieName=CookieValue; SameSite=Lax;
Set-Cookie: CookieName=CookieValue; SameSite=Strict;`
