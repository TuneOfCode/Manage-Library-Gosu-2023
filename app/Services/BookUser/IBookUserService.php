<?php

namespace App\Services\BookUser;

use Illuminate\Http\Request;

interface IBookUserService {
    /**
     * Dịch vụ lấy danh sách sách thuê sách
     */
    public static function getBookUserList(Request $request);
    /**
     * Dịch vụ lấy thông tin sách thuê sách
     */
    public static function getBookUserDetail(Request $request, mixed $id);
    /**
     * Dịch vụ cho mượn sách
     */
    public static function borrowBooks(Request $request);
    /**
     * Dịch vụ phê duyệt cho mượn sách
     */
    public static function approveBorrowingBooks(Request $request);
    /**
     * Dịch vụ từ chối yêu cầu mượn sách
     */
    public static function rejectBorrowingBooks(Request $request);
    /**
     * Dịch vụ trả tiền thuê sách
     */
    public static function payBorrowingBooks(Request $request);
    /**
     * Dịch vụ xác nhận nhận sách
     */
    public static function confirmReceivingBooks(Request $request);
    /**
     * Dich vụ hủy yêu cầu mượn sách
     */
    public static function cancelBorrowingBooks(Request $request);
    /**
     * Dịch vụ trả sách
     */
    public static function returnBooks(Request $request);
    /**
     * Dịch vụ kiểm tra sách trả có quá hạn không
     */
    public static function checkOverdueBooks();
    /**
     * Dịch vụ tăng tiền phụ phí
     */
    public static function increaseExtraMoney();
    /**
     * Dịch vụ kiểm tra không trả sách
     */
    public static function checkNotReturnBooks();
    /**
     * Dịch vụ trả tiền phụ phí (trễ hạn hoặc không trả sách)
     */
    public static function payExtraMoney(Request $request);
    /**
     * Dịch vụ xoá thuê sách
     */
    public static function deleteBookUser(Request $request);
}
