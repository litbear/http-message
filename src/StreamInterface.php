<?php

namespace Psr\Http\Message;

/**
 * Describes a data stream.
 * 描述一个数据流
 *
 * Typically, an instance will wrap a PHP stream; this interface provides
 * a wrapper around the most common operations, including serialization of
 * the entire stream to a string.
 * 典型的说，本接口的实例会包装PHP流；本接口为常用的操作提供包装器，包括将整个流序列
 * 化为字符串
 */
interface StreamInterface
{
    /**
     * Reads all data from the stream into a string, from the beginning to end.
     * 将所有信息从头到尾地从流处理成字符串
     *
     * This method MUST attempt to seek to the beginning of the stream before
     * reading data and read the stream until the end is reached.
     * 本方法在读取数据之前和读取到数据流结尾的时候， 必须（MUST） 尝试寻找流开始的位置，
     *
     * Warning: This could attempt to load a large amount of data into memory.
     * 警告：本方法不要试图向内存中读取大块的数据
     *
     * This method MUST NOT raise an exception in order to conform with PHP's
     * string casting operations.
     * 为了遵循PHP操作字符串的原则，本方法 一定不能（MUST NOT） 抛出异常。
     *
     * @see http://php.net/manual/en/language.oop5.magic.php#object.tostring
     * @return string
     */
    public function __toString();

    /**
     * Closes the stream and any underlying resources.
     * 关闭流以及任何底层的资源
     *
     * @return void
     */
    public function close();

    /**
     * Separates any underlying resources from the stream.
     * 将所有的底层资源与流分离开
     *
     * After the stream has been detached, the stream is in an unusable state.
     * 在所有的流都被分流后，数据流处于不可用的状态
     *
     * @return resource|null Underlying PHP stream, if any
     */
    public function detach();

    /**
     * Get the size of the stream if known.
     * 获取流已知部分的大小
     *
     * @return int|null Returns the size in bytes if known, or null if unknown.
     */
    public function getSize();

    /**
     * Returns the current position of the file read/write pointer
     * 返回文件读写指针的当前位置
     *
     * @return int Position of the file pointer
     * @throws \RuntimeException on error.
     */
    public function tell();

    /**
     * Returns true if the stream is at the end of the stream.
     * 如果指针位于流的末尾，返回true
     *
     * @return bool
     */
    public function eof();

    /**
     * Returns whether or not the stream is seekable.
     * 判断当前流是否是可寻址的
     *
     * @return bool
     */
    public function isSeekable();

    /**
     * Seek to a position in the stream.
     * 在流内寻址
     *
     * @link http://www.php.net/manual/en/function.fseek.php
     * @param int $offset Stream offset
     * @param int $whence Specifies how the cursor position will be calculated
     *     based on the seek offset. Valid values are identical to the built-in
     *     PHP $whence values for `fseek()`.  SEEK_SET: Set position equal to
     *     offset bytes SEEK_CUR: Set position to current location plus offset
     *     SEEK_END: Set position to end-of-stream plus offset.
     * @throws \RuntimeException on failure.
     */
    public function seek($offset, $whence = SEEK_SET);

    /**
     * Seek to the beginning of the stream.
     * 寻找流的开始。
     *
     * If the stream is not seekable, this method will raise an exception;
     * otherwise, it will perform a seek(0).
     * 假如流是不可寻址的，本方法会抛出异常，否则执行seek(0)
     *
     * @see seek()
     * @link http://www.php.net/manual/en/function.fseek.php
     * @throws \RuntimeException on failure.
     */
    public function rewind();

    /**
     * Returns whether or not the stream is writable.
     * 判断当前流是否可写
     *
     * @return bool
     */
    public function isWritable();

    /**
     * Write data to the stream.
     * 将数据写入流
     *
     * @param string $string The string that is to be written.
     * @return int Returns the number of bytes written to the stream.
     * @throws \RuntimeException on failure.
     */
    public function write($string);

    /**
     * Returns whether or not the stream is readable.
     * 判断当前流是否可读
     *
     * @return bool
     */
    public function isReadable();

    /**
     * Read data from the stream.
     * 从流内读取信息
     *
     * @param int $length Read up to $length bytes from the object and return
     *     them. Fewer than $length bytes may be returned if underlying stream
     *     call returns fewer bytes.
     * @return string Returns the data read from the stream, or an empty string
     *     if no bytes are available.
     * @throws \RuntimeException if an error occurs.
     */
    public function read($length);

    /**
     * Returns the remaining contents in a string
     * 以字符串形式返回剩下的内容
     *
     * @return string
     * @throws \RuntimeException if unable to read or an error occurs while
     *     reading.
     */
    public function getContents();

    /**
     * Get stream metadata as an associative array or retrieve a specific key.
     * 以关联数组的形式获取留的元数据，或者根据指定的键获得元数据的值
     *
     * The keys returned are identical to the keys returned from PHP's
     * stream_get_meta_data() function.
     * 指定键获得的返回值与PHP函数stream_get_meta_data()的返回值一样
     *
     * @link http://php.net/manual/en/function.stream-get-meta-data.php
     * @param string $key Specific metadata to retrieve.
     * @return array|mixed|null Returns an associative array if no key is
     *     provided. Returns a specific key value if a key is provided and the
     *     value is found, or null if the key is not found.
     */
    public function getMetadata($key = null);
}
