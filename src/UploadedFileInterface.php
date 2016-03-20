<?php

namespace Psr\Http\Message;

/**
 * Value object representing a file uploaded through an HTTP request.
 * 用于代表通过HTTP请求进行上传的文件的对象
 *
 * Instances of this interface are considered immutable; all methods that
 * might change state MUST be implemented such that they retain the internal
 * state of the current instance and return an instance that contains the
 * changed state.
 * 本接口的实例应是不变的；所有可能引起状态改变的方法 必须（MUST） 
 * 保留原对象的状态不变，同时返回一个包含新状态的新对象。
 */
interface UploadedFileInterface
{
    /**
     * Retrieve a stream representing the uploaded file.
     * 获得代表被上传文件的流
     *
     * This method MUST return a StreamInterface instance, representing the
     * uploaded file. The purpose of this method is to allow utilizing native PHP
     * stream functionality to manipulate the file upload, such as
     * stream_copy_to_stream() (though the result will need to be decorated in a
     * native PHP stream wrapper to work with such functions).
     * 本方法必须返回一个 StreamInterface 接口的实例，以代表被上传的文件。本方法
     * 的目的在于使用原生的PHP流功能操作文件上传，例如 stream_copy_to_stream()
     * 函数（不过方法的结果需要被原生的PHP流包裹以用于本方法的操作）
     *
     * If the moveTo() method has been called previously, this method MUST raise
     * an exception.
     * 假如在调用本方法之前调用了 moveTo() 方法，则本方法 必须（MUST）抛出异常
     *
     * @return StreamInterface Stream representation of the uploaded file.
     * @throws \RuntimeException in cases when no stream is available or can be
     *     created.
     */
    public function getStream();

    /**
     * Move the uploaded file to a new location.
     * 将被上传的文件移动到新位置
     *
     * Use this method as an alternative to move_uploaded_file(). This method is
     * guaranteed to work in both SAPI and non-SAPI environments.
     * Implementations must determine which environment they are in, and use the
     * appropriate method (move_uploaded_file(), rename(), or a stream
     * operation) to perform the operation.
     * 本方法与move_uploaded_file()等价，本方法应同时在 SAPI和非SASPI 的环境中
     * 等价。本方法的实现必须判断当前处于那种环境，并使用适当的方法(
     * move_uploaded_file(), rename(), 或流操作) 去执行相应的操作。
     *
     * $targetPath may be an absolute path, or a relative path. If it is a
     * relative path, resolution should be the same as used by PHP's rename()
     * function.
     * $targetPath 可以使绝对路径或相对路径，假如是相对路径，那么解析结果应当
     * 等同于用于rename()方法（这句不太懂啊）
     *
     * The original file or stream MUST be removed on completion.
     * 原始的文件或流 必须（MUST）在完成后移除。
     *
     * If this method is called more than once, any subsequent calls MUST raise
     * an exception.
     * 假如本方法的实现被多次调用，那么所有的非首次调用都 必须（MUST）抛出异常
     *
     * When used in an SAPI environment where $_FILES is populated, when writing
     * files via moveTo(), is_uploaded_file() and move_uploaded_file() SHOULD be
     * used to ensure permissions and upload status are verified correctly.
     * 当用于 SAPI环境中时$_FILES 全局变量被填充，当使用moveTo()方法写入文件时，
     * is_uploaded_file() 和 move_uploaded_file() 方法 应当（SHOULD） 被用于确保
     * 权限和上传状态的正确验证。
     *
     * If you wish to move to a stream, use getStream(), as SAPI operations
     * cannot guarantee writing to stream destinations.
     * 假如你希望将文件移动到流中，请使用getStream()方法，虽然SAPI操作不能
     * 保证成功写入到流
     *
     * @see http://php.net/is_uploaded_file
     * @see http://php.net/move_uploaded_file
     * @param string $targetPath Path to which to move the uploaded file.
     * @throws \InvalidArgumentException if the $path specified is invalid.
     * @throws \RuntimeException on any error during the move operation, or on
     *     the second or subsequent call to the method.
     */
    public function moveTo($targetPath);
    
    /**
     * Retrieve the file size.
     * 获得文件的大小
     *
     * Implementations SHOULD return the value stored in the "size" key of
     * the file in the $_FILES array if available, as PHP calculates this based
     * on the actual size transmitted.
     * 本方法的实现 应该（SHOULD） 在可用的情况下返回储存于$_FILES 全局变量的
     *  "size" 元素的值。虽然PHP以传输的真实大小计算文件大小。
     * 
     *
     * @return int|null The file size in bytes or null if unknown.
     */
    public function getSize();
    
    /**
     * Retrieve the error associated with the uploaded file.
     * 取出与上传文件相关的错误
     *
     * The return value MUST be one of PHP's UPLOAD_ERR_XXX constants.
     * 本方法的返回值 必须（MUST） 是形如UPLOAD_ERR_XXX的常量
     *
     * If the file was uploaded successfully, this method MUST return
     * UPLOAD_ERR_OK.
     * 假如文件上传成功，则本方法 一定（MUST） 返回UPLOAD_ERR_OK
     *
     * Implementations SHOULD return the value stored in the "error" key of
     * the file in the $_FILES array.
     * 本方法的实现 应该（SHOULD） 返回储存于$_FILES 全局变量的 "name" 元素
     * 的值
     *
     * @see http://php.net/manual/en/features.file-upload.errors.php
     * @return int One of PHP's UPLOAD_ERR_XXX constants.
     */
    public function getError();
    
    /**
     * Retrieve the filename sent by the client.
     * 获取被上传文件的原始文件名
     *
     * Do not trust the value returned by this method. A client could send
     * a malicious filename with the intention to corrupt or hack your
     * application.
     * 不要相信本方法返回的值。因为客户端可以伪造文件媒体信息以攻击服务器应用
     *
     * Implementations SHOULD return the value stored in the "name" key of
     * the file in the $_FILES array.
     * 本方法的实现 应该（SHOULD） 返回储存于$_FILES 全局变量的 "name" 元素
     * 的值
     *
     * @return string|null The filename sent by the client or null if none
     *     was provided.
     */
    public function getClientFilename();
    
    /**
     * Retrieve the media type sent by the client.
     * 获得客户端传来的文件媒体信息
     *
     * Do not trust the value returned by this method. A client could send
     * a malicious media type with the intention to corrupt or hack your
     * application.
     * 不要相信本方法返回的值。因为客户端可以伪造文件媒体信息以攻击服务器应用
     *
     * Implementations SHOULD return the value stored in the "type" key of
     * the file in the $_FILES array.
     * 本方法的实现 应该（SHOULD） 返回储存于$_FILES 全局变量的 "type" 元素
     * 的值
     *
     * @return string|null The media type sent by the client or null if none
     *     was provided.
     */
    public function getClientMediaType();
}
